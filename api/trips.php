<?php
$fetch_only_if_needed = true;
require('updateCache.php');

foreach(reset($routes)["routes"] as $route) {
	if($route["routeShortName"] == $_GET["trip"]) {
	    foreach(reset($trips)["trips"] as $trip) {
		if($trip["routeId"] == $route["routeId"] && $trip["tripId"] == $_GET["route"]) {
        		$array = [
        			"tripHeadsign" => $trip["tripHeadsign"]];
        		echo json_encode($array);
        		break;
        	}
	    }
	}
}

if($cache_expired) {
	pclose(popen('php updateCache.php &',"r")); // because closing the connection from the main script doesn't always work
}
