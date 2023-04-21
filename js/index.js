// Request libraries when needed, not in the script tag.
// google api libraries
const { Map } = await google.maps.importLibrary("maps");
const { Marker } = await google.maps.importLibrary("marker");

// various variables
let map;
let markers = [];
let locations;
let placeholderMarker;

// dom elements
const publicLocationEntry = document.querySelector(
  "#public_location_entry_template"
).content.firstElementChild;
const privateLocationEntry = document.querySelector(
  "#private_location_entry_template"
).content.firstElementChild;
const showIcon = document.querySelector("#show_icon_template").content
  .firstElementChild;
const hideIcon = document.querySelector("#hide_icon_template").content
  .firstElementChild;
const pubLocationsList = document.querySelector("#public_locations_list");
const privLocationsList = document.querySelector("#private_locations_list");
const authenticated = document.querySelector("#authenticated") ? true : false;

async function initMap() {
  await getLocations();

  // get random starting location from the fetched ones
  let randomLocation;
  let startingZoom = 5;
  if (authenticated) {
    // random starting public if private ones don't exist
    if (locations.privateLocations.length !== 0) {
      randomLocation =
        locations.privateLocations[
          Math.floor(Math.random() * locations.privateLocations.length)
        ];
    } else if (locations.publicLocations.length !== 0) {
      randomLocation =
        locations.publicLocations[
          Math.floor(Math.random() * locations.publicLocations.length)
        ];
    } else {
      randomLocation = { lat: 0, lng: 0 };
      startingZoom = 2;
    }
  } else {
    // random starting location if public ones don't exist
    if (locations.length) {
      randomLocation = locations[Math.floor(Math.random() * locations.length)];
    } else {
      randomLocation = { lat: 0, lng: 0 };
      startingZoom = 2;
    }
  }

  let startLocation = {
    lat: Number(randomLocation.lat),
    lng: Number(randomLocation.lng)
  };

  map = new Map(document.getElementById("map"), {
    center: startLocation,
    zoom: startingZoom
  });

  if (authenticated) {
    for (let location of locations.publicLocations) {
      addMarker(location);
      pubLocationsList.appendChild(createPublicLocationEntry(location));
    }
    for (let location of locations.privateLocations) {
      addMarker(location);
      privLocationsList.appendChild(createPrivateLocationEntry(location));
    }
  } else {
    for (let location of locations) {
      addMarker(location);
      pubLocationsList.appendChild(createPublicLocationEntry(location));
    }
  }

  if (authenticated) {
    map.addListener("click", mapClickHandler);
    document
      .querySelector("#add_location_button")
      .addEventListener("click", addLocation);
  }
}
initMap();

function mapClickHandler(e) {
  // if not defined create marker
  placeholderMarker ||= new Marker({ label: "+" });

  placeholderMarker.setMap(map);
  placeholderMarker.setPosition(e.latLng);

  document.querySelector("#add_location_button").removeAttribute("disabled");
}

async function addLocation() {
  let nameInput = document.querySelector("#location_name_input");
  let publicityInput = document.querySelector("#location_publicity_input");

  if (placeholderMarker == null || nameInput.value === "") return;

  let data = new FormData();
  data.append("name", nameInput.value);
  data.append("lat", placeholderMarker.position.lat());
  data.append("lng", placeholderMarker.position.lng());
  // append only if checked
  publicityInput.checked && data.append("public", "true");

  let res = await fetch("./api/createLocation.php", {
    method: "POST",
    body: data
  });

  if (res.ok) {
    //reset inputs and marker
    let addButton = document.querySelector("#add_location_button");
    addButton.setAttribute("disabled", "");
    nameInput.value = "";
    publicityInput.checked = false;
    placeholderMarker.setMap(null);

    // extract location from returned object
    let { location } = await res.json();
    addMarker(location);
    privLocationsList.appendChild(createPrivateLocationEntry(location));
  }
}

async function getLocations() {
  let res = await fetch("./api/getLocations.php", {
    method: "GET"
  });

  res = await res.json();
  if (authenticated) {
    locations = {
      publicLocations: res.publicLocations,
      privateLocations: res.privateLocations
    };
  } else {
    locations = res.locations;
  }
}

function addMarker(location) {
  markers.push({
    id: location.location_id,
    marker: new Marker({
      position: {
        lat: Number(location.lat),
        lng: Number(location.lng)
      },
      map,
      title: location.name
    })
  });
}

// creates a copy of the entry_location template and returns it
function createPrivateLocationEntry(location) {
  let clone = privateLocationEntry.cloneNode(true);
  let publicButton = clone.querySelector("#template_toggle_publicity_button");
  let editButton = clone.querySelector("#template_edit_button");
  let deleteButton = clone.querySelector("#template_delete_button");
  clone.id = `entry-${location.location_id}`;
  clone.querySelector("#template_private_location_name").textContent =
    location.name;
  publicButton.id = "";
  publicButton.addEventListener("click", () => changeVisibility(location));

  editButton.id = "";
  editButton.addEventListener("click", () => console.log("TODO"));

  deleteButton.id = "";
  deleteButton.addEventListener("click", () => deleteLocation(location));

  // add panTo click event
  clone.addEventListener("click", () => panToMarker(location.location_id));

  return clone;
}

function createPublicLocationEntry(location) {
  let clone = publicLocationEntry.cloneNode(true);
  let nameSpan = clone.querySelector("#template_public_location_name");
  let authorSpan = clone.querySelector("#template_public_location_author");

  clone.id = `entry-${location.location_id}`;

  nameSpan.id = "";
  nameSpan.textContent = location.name;
  authorSpan.id = "";
  authorSpan.textContent = location.username;

  // add panTo click event
  clone.addEventListener("click", () => panToMarker(location.location_id));

  return clone;
}

async function deleteLocation(location) {
  let res = await fetch(
    `./api/deleteLocation.php?location_id=${location.location_id}`
  );

  if (!res.ok) return;
  // removing marker from map
  let { marker } = markers.find((val) => val.id === location.location_id);
  marker.setMap(null);
  // deleting marker by removing it from the array
  markers = markers.filter((val) => val.id !== location.location_id);

  document.querySelector(`#entry-${location.location_id}`).remove();
}

function panToMarker(id) {
  let { marker } = markers.find((val) => val.id === id);
  map.panTo(marker.position);
}

function togglePublicList() {
  const pubLocationsSection = document.querySelector(
    "#public_locations_section"
  );

  pubLocationsSection.classList.toggle("grow");
  pubLocationsSection.classList.toggle("shrink");
  pubLocationsSection.classList.toggle("basis-auto");
  pubLocationsSection.classList.toggle("h-0");
  pubLocationsList.classList.toggle("hidden");
  document
    .querySelector("#toggle_public_locations")
    .classList.toggle("rotate-180");
}

document
  .querySelector("#toggle_public_locations")
  .addEventListener("click", togglePublicList);

function togglePrivateList() {
  const privLocationsSection = document.querySelector(
    "#private_locations_section"
  );

  privLocationsSection.classList.toggle("grow");
  privLocationsSection.classList.toggle("shrink");
  privLocationsSection.classList.toggle("basis-auto");
  privLocationsSection.classList.toggle("h-0");
  privLocationsList.classList.toggle("hidden");
  document
    .querySelector("#toggle_private_locations")
    .classList.toggle("rotate-180");
}
if (authenticated) {
  document
    .querySelector("#toggle_private_locations")
    .addEventListener("click", togglePrivateList);
}
