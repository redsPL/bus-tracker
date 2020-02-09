<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
	<title>The Sakamoto Bus Search</title>
</head>
<body>

<style>
#data {
  padding: 2px;
	z-index:1000000;
	position: absolute;
	width: 300px;
	background-color: rgba(0, 0, 0, 0.6);;
	color: white;
	right: 10px;
}
@media only screen and (max-width: 768px) {
  #data {
    width: 100%;
    right: 0px;
    bottom: 0px;
  }
}
.olControlAttribution {
	bottom: 0px;
}
</style>
  <div id="data">
	<div id="update"></div>
	<div id="vehicle"></div>
	<div id="search"><input type="text" id="line"><br>
	<button type="button" id="refresh">Refresh</button></div>
	<div id="counter"></div>
  </div>
  <div id="mapdiv"></div>
  <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script>
  var select = -1;
  var data = "";
  var markerList = {};
  var counter = 0;
  var route="";
  $("#line").val("");

  $("#refresh").click(loadPoints);

function lonLat(lon, lat) {
	return new OpenLayers.LonLat(lon, lat ).transform(
			new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
			map.getProjectionObject() // to Spherical Mercator Projection
          	);
}
  function loadPoints() {
  	counter = 0;
	$.ajax({url:"/api/gpsPositions.php", dataType: "json", success: function(result) {
		data = result;
		$("#update").html("last update: "+data["LastUpdateData"]);
		markers.clearMarkers();
		data["Vehicles"].forEach(setPoints);
		$("#counter").html("bus count: "+counter);
	}, fail: function() {
		console.log("AJAX machine broke");
	}});
  }
  function setPoints(item, index) {
  	var line = $("#line").val();
	if(line == "" || item.Line == line) {
		counter++;
  	
		var bus = data["Vehicles"][index];
    markerList[item.VehicleId] = new OpenLayers.Marker(lonLat(item.Lon, item.Lat));
    markers.addMarker(markerList[item.VehicleId]);
        if (select == bus["VehicleId"]) {
      markerList[item.VehicleId].setUrl("/marker_g.png");
    }
    markerList[item.VehicleId].events.register('click', markerList[item.VehicleId], function(evt) { busInfo(bus); OpenLayers.Event.stop(evt); });
		markerList[item.VehicleId].events.register('touchstart', markerList[item.VehicleId], function(evt) { busInfo(bus); OpenLayers.Event.stop(evt); });
    	}
    }
  function busInfo(bus) {
    if(select != -1) {
      //markerList[id] = new OpenLayers.Marker(markerList[id]["lonlat"]);
      markerList[select].setUrl("/marker.png");
    }
    select = bus["VehicleId"];

    $.ajax({url:"/api/trips.php?trip="+bus["Line"]+"&route="+bus["Route"], dataType: "json", success: function(result) {
    console.log("asdf");
        markerList[select].setUrl("/marker_g.png");
	$("#vehicle").html("Line: "+bus["Line"]+" Vehicle:"+bus["VehicleCode"]+"<br>Speed: "+bus["Speed"]+"km/h<br>Delay: "+bus["Delay"]+"<br>Route: "+result["tripHeadsign"]+"<br>Last heard: "+bus["DataGenerated"]);
    }});

  }


    map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());
    var markers = new OpenLayers.Layer.Markers( "Markers" );
    map.addLayer(markers);
    
    loadPoints();

    map.setCenter (lonLat(18.648844, 54.375667), 12);
    setInterval(loadPoints, 12000);


  </script>
</body></html>