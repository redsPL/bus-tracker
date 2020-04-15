<?php
$cache_expired = false;
$cache_routes = "cache/routes.json";
$cache_trips = "cache/trips.json";
$url_routes = "https://ckan.multimediagdansk.pl/dataset/c24aa637-3619-4dc2-a171-a23eec8f2172/resource/22313c56-5acf-41c7-a5fd-dc5dc72b3851/download/routes.json";
$url_trips = "https://ckan.multimediagdansk.pl/dataset/c24aa637-3619-4dc2-a171-a23eec8f2172/resource/b15bb11c-7e06-4685-964e-3db7775f912f/download/trips.json";
$ratelimit = 86400;
$time = time() - $ratelimit;

function updateTripsCache() {
	global $url_trips, $cache_trips;
	$trips_json = file_get_contents($url_trips);
	file_put_contents($cache_trips, $trips_json);
	return json_decode($trips_json, 1);
}

function updateRoutesCache() {
	global $url_routes, $cache_routes;
	$routes_json = file_get_contents($url_routes);
	file_put_contents($cache_routes, $routes_json);
	return json_decode($routes_json, 1);
}

if(file_exists($cache_trips)) {
	$trips = json_decode(file_get_contents("cache/trips.json"), 1);
	$gaitTime = new DateTime(reset($trips)["lastUpdate"], new DateTimeZone("Europe/Warsaw"));
	if ($gaitTime->getTimestamp() < $time) {
		$cache_expired = true;
		if (!isset($fetch_only_if_needed)) updateTripsCache();
	}
} else {
	$trips = updateTripsCache();
}
if(file_exists($cache_routes)) {
	$routes = json_decode(file_get_contents("cache/routes.json"), 1);
	$gaitTime = new DateTime(reset($routes)["lastUpdate"], new DateTimeZone("Europe/Warsaw"));
	if ($gaitTime->getTimestamp() < $time) {
		$cache_expired = true;
		if (!isset($fetch_only_if_needed)) updateRoutesCache();
	}
} else {
	$routes = updateRoutesCache();
}

