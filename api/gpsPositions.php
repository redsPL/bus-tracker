<?php
if(file_exists("cache/gpsPositions")) {
	$data = file_get_contents("cache/gpsPositions");
	$json = json_decode($data,1);
	$time = time() - 30;
	$gaitTime = strtotime($json["LastUpdateData"]) - 7200;
	if ($gaitTime < $time) {
	    $data = file_get_contents("http://ckan2.multimediagdansk.pl/gpsPositions");
		file_put_contents("cache/gpsPositions", $data);
	}
} else {
	$data = file_get_contents("http://ckan2.multimediagdansk.pl/gpsPositions");
	file_put_contents("cache/gpsPositions", $data);
}

if(isset($data)) {
    echo $data;
}
