// Request libraries when needed, not in the script tag.
const { Map } = await google.maps.importLibrary("maps");
const { Marker } = await google.maps.importLibrary("marker");

let map;
let markers = [];
let locations;
const locationEntry = document.querySelector(
  "#location_entry_template"
).firstElementChild;
const showIcon = document.querySelector(
  "#show_icon_template"
).firstElementChild;
const hideIcon = document.querySelector(
  "#hide_icon_template"
).firstElementChild;
const authenticated = document.querySelector("#authenticated") ? true : false;

// initMap is now async
async function initMap() {
  await getLocations();

  // get random starting location from the fetched ones
  let randomLocation;
  if (authenticated) {
    randomLocation =
      locations.privateLocations[
        Math.floor(Math.random() * locations.privateLocations.length)
      ];
  } else {
    randomLocation = locations[Math.floor(Math.random() * locations.length)];
  }

  let startLocation = {
    lat: Number(randomLocation.lat),
    lng: Number(randomLocation.lng)
  };

  // Short namespaces can be used.
  map = new Map(document.getElementById("map"), {
    center: startLocation,
    zoom: 5
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

function collapseAccordion(e) {}
