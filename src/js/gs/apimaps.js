mapboxgl.accessToken = "pk.eyJ1IjoibWp2YWxlbnp1ZWxhIiwiYSI6ImNrb2Fmdm9zZDBpM28ybnFtYTQ2Z2MwMnYifQ.ZY3jTw0-6tjUSOOJXJHsdw";

var map = new mapboxgl.Map({
  container: "map",
  style: "mapbox://styles/mapbox/navigation-preview-night-v4",
  center: [-68.1330326, -16.5010926],
  zoom: 13,
});

// version satelital : satellite-streets-v11

var marker;
var latitudeInput = document.getElementById("latitud");
var longitudeInput = document.getElementById("longitud");

map.on("click", function (e) {
  var coordinates = e.lngLat;
  var latitude = coordinates.lat;
  var longitude = coordinates.lng;

  // Eliminar el marcador anterior (si existe)
  if (marker) {
    marker.remove();
  }

  // Crear el nuevo marcador
  marker = new mapboxgl.Marker().setLngLat(coordinates).addTo(map);

  // Mostrar latitud y longitud en los campos de texto
  latitudeInput.value = latitude;
  longitudeInput.value = longitude;
});

// Agregar rutas

/*
map.addControl(
	new MapboxDirections({
		accessToken: mapboxgl.accessToken,
	}),
	"top-left"
	);

*/

// Agregar barra de zoom
map.addControl(new mapboxgl.NavigationControl());

// Edificios en 3D

map.on("load", () => {
  const layers = map.getStyle().layers;
  const labelLayerId = layers.find(
    (layer) => layer.type === "symbol" && layer.layout["text-field"]
  ).id;

  map.addLayer(
    {
      id: "add-3d-buildings",
      source: "composite",
      "source-layer": "building",
      filter: ["==", "extrude", "true"],
      type: "fill-extrusion",
      minzoom: 15,
      paint: {
        "fill-extrusion-color": "#aaa",
        "fill-extrusion-height": [
          "interpolate",
          ["linear"],
          ["zoom"],
          15,
          0,
          15.05,
          ["get", "height"],
        ],
        "fill-extrusion-base": [
          "interpolate",
          ["linear"],
          ["zoom"],
          15,
          0,
          15.05,
          ["get", "min_height"],
        ],
        "fill-extrusion-opacity": 0.6,
      },
    },
    labelLayerId
  );
});
