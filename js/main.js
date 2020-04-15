const markerInactive = "/img/marker.png";
const markerActive = "/img/marker_g.png";
let select = -1;
let data = "";
let markerList = {};
let counter = 0;
let route="";
$("#line").val("");
$("#refresh").click(loadPoints);

function lonLat(lon, lat) {
	return new OpenLayers.LonLat(lon, lat ).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
}
function loadPoints() {
	counter = 0;
	$.ajax({url:"/api/gpsPositions.php", dataType: "json", success: function(result) {
		data = result;
		$("#update").html("Last update: "+data["LastUpdateData"]);
		markers.clearMarkers();
		data["Vehicles"].forEach(setPoints);
		$("#counter").html("Vehicle count: "+counter);
	}, fail: function() {
		console.log("AJAX machine broke");
	}});
}
function setPoints(item, index) {
	let line = $("#line").val();
	if(line == "" || item.Line == line) {
		counter++;
		let bus = data["Vehicles"][index];
		markerList[item.VehicleId] = new OpenLayers.Marker(lonLat(item.Lon, item.Lat));
		markers.addMarker(markerList[item.VehicleId]);
		if (select == bus["VehicleId"]) {
			markerList[item.VehicleId].setUrl(markerActive);
		}
		markerList[item.VehicleId].events.register('click', markerList[item.VehicleId], function(evt) { busInfo(bus); OpenLayers.Event.stop(evt); });
		markerList[item.VehicleId].events.register('touchstart', markerList[item.VehicleId], function(evt) { busInfo(bus); OpenLayers.Event.stop(evt); });
	}
}
function busInfo(bus) {
	if(select != -1) {
		markerList[select].setUrl(markerInactive);
	}
	select = bus["VehicleId"];
	$.ajax({url:"/api/trips.php?trip="+bus["Line"]+"&route="+bus["Route"], dataType: "json", success: function(result) {
		markerList[select].setUrl(markerActive);
		$("#vehicle").html("Line: "+bus["Line"]+" Vehicle:"+bus["VehicleCode"]+"<br>Speed: "+bus["Speed"]+"km/h<br>Delay: "+bus["Delay"]+"<br>Route: "+result["tripHeadsign"]+"<br>Last heard: "+bus["DataGenerated"]);
	}});
}

map = new OpenLayers.Map("mapdiv");
map.addLayer(new OpenLayers.Layer.OSM());
const markers = new OpenLayers.Layer.Markers( "Markers" );
map.addLayer(markers);
    
loadPoints();

map.setCenter(lonLat(18.648844, 54.375667), 12);
setInterval(loadPoints, 12000);
