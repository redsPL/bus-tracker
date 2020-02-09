<?php

$data_trips = json_decode(file_get_contents("cache/trips.json"),1);
$data = json_decode(file_get_contents("cache/routes.json"),1);
$id = 0;

//foreach(reset($data_trips)["trips"] as $trip) {
//	if($trip["routeShortName"] == $_GET["trip"]) {
//		$id = $trip["routeId"];
//	}
//}

foreach(reset($data)["routes"] as $route) {
	if($route["routeShortName"] == $_GET["trip"]) {
	    foreach(reset($data_trips)["trips"] as $trip) {
		if($trip["routeId"] == $route["routeId"] && $trip["tripId"] == $_GET["route"]) {
        		echo json_encode($trip);
        	}
	    }
	}
}


