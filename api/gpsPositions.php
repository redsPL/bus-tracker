<?php
$url = "http://ckan2.multimediagdansk.pl/gpsPositions";
$cache = "cache/gpsPositions.json";
$cache_expired = false;
$ratelimit = 20;

if(file_exists($cache)) {
	$data = json_decode(file_get_contents($cache), 1);
	$time = time() - $ratelimit;
	$gaitTime = new DateTime($data["LastUpdateData"], new DateTimeZone("Europe/Warsaw"));
	if ($gaitTime->getTimestamp() < $time) $cache_expired = true;
} else {
	$cache_expired = true;
}

if($cache_expired == true) {
	$json = file_get_contents($url);
	$data = json_decode($json, 1);
	file_put_contents($cache, $json);
}

if(isset($data)) {
//    echo json_encode($data); // strip gibberish
	$vehicles = ["LastUpdateData" => $data["LastUpdateData"], "Vehicles" => []];
    foreach($data["Vehicles"] as $vehicle) {
    	$array = [
    		"DataGenerated" => $vehicle["DataGenerated"],
    		"Line" => $vehicle["Line"],
    		"Route" => $vehicle["Route"],
    		"VehicleId" => $vehicle["VehicleId"],
    		"VehicleCode" => $vehicle["VehicleCode"],
    		"Speed" => $vehicle["Speed"],
    		"Delay" => $vehicle["Delay"],
    		"Lat" => $vehicle["Lat"],
    		"Lon" => $vehicle["Lon"]
		];
    	array_push($vehicles["Vehicles"],$array);
    }
    echo json_encode($vehicles);
}
