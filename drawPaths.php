<?php

$pathFiles = [
	'path-02-27-23-35-16.json',
	'path-02-27-23-35-16-resampled.json'
];

foreach ($pathFiles as $file) {
	$data = json_decode(file_get_contents($file), true);
	$path = $data['path'];
	drawPaths($path, $file);
}


function drawPaths($path, $name) {
	$image = imagecreatetruecolor(800, 800);
	$black = imagecolorallocate($image, 0, 0, 0);
	$white = imagecolorallocate($image, 255, 255, 255);
	imagefill($image, 0, 0, $white);

	$previous = array_shift($path);
	foreach ($path as $state) {
		foreach ($state as $id => $uav) {
			$location1 = $previous[$id]['pointParticle']['location'];
			$x1 = $location1['x'];
			$y1 = $location1['y'];

			$location2 = $uav['pointParticle']['location'];
			$x2 = $location2['x'];
			$y2 = $location2['y'];
			imageline($image, $x1, $y1, $x2, $y2, $black);
		}
		$previous = $state;
	}

	imagepng($image, $name . '.png');
}


