// Request libraries when needed, not in the script tag.
const { Map } = await google.maps.importLibrary("maps");
const { Marker } = await google.maps.importLibrary("marker");

let map;
let markers = [];
let locations;
const publicLocationEntry = document.querySelector(
  "#public_location_entry_template"
).firstElementChild;
const privateLocationEntry = document.querySelector(
  "#private_location_entry_template"
).firstElementChild;
const showIcon = document.querySelector(
  "#show_icon_template"
).firstElementChild;
const hideIcon = document.querySelector(
  "#hide_icon_template"
).firstElementChild;
const authenticated = document.querySelector("#authenticated") ? true : false;
const pubLocationsList = document.querySelector("#public_locations_list");
const privLocationsList = document.querySelector("#private_locations_list");

// initMap is now async
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

  // Short namespaces can be used.
  map = new Map(document.getElementById("map"), {
    center: startLocation,
    zoom: startingZoom
  });

  if (authenticated) {
    for (let location of locations.publicLocations) addMarker(location);
    for (let location of locations.privateLocations) addMarker(location);
  } else {
    for (let location of locations) addMarker(location);
  }
}

initMap();

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
    id: location.id,
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

function createLocationEntry() {
  if (authenticated) {
  }
}

function togglePublicList() {
  const pubLocationsSection = document.querySelector(
    "#public_locations_section"
  );

  //if it's collapsed
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

document
  .querySelector("#toggle_private_locations")
  .addEventListener("click", togglePrivateList);
