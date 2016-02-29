<?php

//$pathFiles = [
////	'path1456102594.json',
////	'path1456102803.json',
////	'path1456154221.json',
////	'path1456154553.json',
////	'path-02-22-16-39-16.json',
////	'path-02-22-16-44-16.json',
////	'path-02-27-22-56-16.json',
////	'path-02-27-22-56-16-resampled.json',
//	'path-02-27-23-02-16.json',
//	'path-02-27-23-02-16-resampled.json'
//];
//foreach ($pathFiles as $pathFile) {
//	$data = json_decode(file_get_contents($pathFile), true);
//	$path = $data['path'];
//	$timeStep = 1;
//	$sampleCount = count($path);
//	$totalTime = $timeStep * $sampleCount;	//total time to fly the path
//	$currentFrequency = $sampleCount / $totalTime;	//samples per second
//	$maxFrequency = 70;
//	$maxSampleCount = 2700;
//	$maxAvailableFrequency = $maxSampleCount / $totalTime;
//	$newFrequency = min($maxAvailableFrequency, $maxFrequency);	//pokud je maxFrequency větší než maxAvailableFrequency, bude nastavena maxAvailable, jinak maaxFrequency
//	$ratio = floor($newFrequency / $currentFrequency);	//bude ratio krát více vzorků
//	echo 'sample count: ' . $sampleCount . PHP_EOL;
//	echo 'ratio: ' . $ratio . PHP_EOL;
//	echo 'new sample count: ' . $sampleCount * $ratio . PHP_EOL;
//	echo 'new frequency: ' . $newFrequency . PHP_EOL;
//	echo PHP_EOL;
//}

//echo angleToLeft(0, 1) . PHP_EOL;
//echo angleToRight(0, 1) . PHP_EOL;
//
//echo angleToLeft(0, 2) . PHP_EOL;
//echo angleToRight(0, 2) . PHP_EOL;
//
//echo angleToLeft(2, 1) . PHP_EOL;
//echo angleToRight(2, 1) . PHP_EOL;
//
//echo angleToLeft(1, 2) . PHP_EOL;
//echo angleToRight(1, 2) . PHP_EOL;

function angleToLeft($ang1, $ang2) {
	$TOLERANCE = 10e-10;
	$ret = $ang2 - $ang1;

	while ($ret > 2 * M_PI - $TOLERANCE) {
		$ret -= 2 * M_PI;
	}
	while ($ret < - $TOLERANCE) {
		$ret += 2 * M_PI;
	}
	return $ret;
}

function angleToRight($ang1, $ang2) {
	$TOLERANCE = 10e-10;
	$ret = $ang2 - $ang1;

	while ($ret > $TOLERANCE) {
		$ret -= 2 * M_PI;
	}
	while ($ret < -2 * M_PI + $TOLERANCE) {
		$ret += 2 * M_PI;
	}

	return $ret;
}


$pathFiles = [
//	'path-02-28-23-35-16.json',
	'path-02-28-23-35-16-resampled.json'
];

foreach ($pathFiles as $file) {
	$data = json_decode(file_get_contents($file), true);
	$path = $data['path'];
	foreach ($path as $state) {
//		foreach ($state as $id => $uav) {
		$uav = $state['51'];
			$location = $uav['pointParticle']['location'];
			$x = $location['x'];
			$y = $location['y'];
			echo "$x, $y" . PHP_EOL;
//		}
	}
}



