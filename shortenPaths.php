<?php

require_once __DIR__ . '/runDubinsOptimization.php';

$file = 'C:\wamp\www\UAVUtils\dubinsOptimizations\path-03-27-18-09-16-before-dubins.json';

//extract one uav path
$data = json_decode(file_get_contents($file), true);
$path = $data['path'];

$chosenUAV = null;
$newData = [];
$newData['map'] = $data['map'];
$newPath = [];

$limit = 35;
$i = 0;
foreach ($path as $state) {
	foreach ($state as $id => $uav) {
		if (!$chosenUAV) {
			$chosenUAV = $id;
		}

		if ($id != $chosenUAV) {
			continue;
		}

		$newPath[][$id] = $uav;
	}
	$i++;
	if ($i > $limit) {
		break;
	}
}

$newData['path'] = $newPath;
file_put_contents('dubinsOptimizations\oneUAV.json', json_encode($newData, JSON_PRETTY_PRINT));
