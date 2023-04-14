let map;
// initMap is now async
async function initMap() {
  startLocation = { lat: -34.397, lng: 150.644 };

  // Request libraries when needed, not in the script tag.
  const { Map } = await google.maps.importLibrary("maps");
  // Short namespaces can be used.
  map = new Map(document.getElementById("map"), {
    center: startLocation,
    zoom: 8
  });
}

initMap();
