<?php

$starttime = microtime(true);

$pathFiles = [
//	"C:\\Users\\Azathoth\\Documents\\Visual Studio 2015\\Projects\\SwarmDeployment\\Win32\\Release\\output\\path-03-27-18-09-16-before-dubins.json",
	"C:\\Users\\Azathoth\\Documents\\Visual Studio 2015\\Projects\\SwarmDeployment\\Win32\\ReleaseNoGui\\output\\path-04-17-16-23-49-00-optimized.json",
//	'C:\wamp\www\UAVUtils\dubinsOptimizations\oneUAV.json',
//	'C:\wamp\www\UAVUtils\dubinsOptimizations\oneUAVoptimized.json',
//	'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\Release\output\path-03-27-17-49-16-before-dubins.json',
//	'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\output\path-04-26-16-01-18-03-optimized.json',
//	'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\output\path-04-26-16-01-14-46-optimized.json',
//	'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\output\path-04-26-16-01-13-28-optimized.json',
//	'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\output\path-04-26-16-01-10-35-optimized.json',
//	'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\output\path-04-26-16-01-32-11-optimized.json',
//	'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\output\path-04-26-16-01-24-04-optimized.json'

];

foreach ($pathFiles as $file) {
	$data = json_decode(file_get_contents($file), true);
	$size = $data['map']['size'];
	$image = imagecreatetruecolor($size, $size);
	drawMap($data['map'], $image);
//	drawPaths($data['path'], $image);
	imagepng($image, $file . '.png');
}

function drawPaths($path, $image) {
	$colors = [];

	$previous = array_shift($path);
	foreach ($path as $state) {
		foreach ($state as $id => $uav) {
			$location1 = $previous[$id]['pointParticle']['location'];
			$x1 = $location1['x'];
			$y1 = $location1['y'];

			$location2 = $uav['pointParticle']['location'];
			$x2 = $location2['x'];
			$y2 = $location2['y'];

			if (!isset($colors[$id])) {
				$colors[$id] = imagecolorallocate($image, (120 + $id * 100) % 256, ($id * 100) % 256, (255 - $id * 100) % 256);
			}

			$color = $colors[$id];
			imageline($image, $x1, $y1, $x2, $y2, $color);
		}
		$previous = $state;
	}

}

function drawMap($map, $image) {
	$white = imagecolorallocate($image, 255, 255, 255);
	imagefill($image, 0, 0, $white);
	$obstacles = $map['obstacles'];
	$goals = $map['goals'];
	$obstacleAmplification = 30;
	foreach ($obstacles as $obstacle) {
		$x1 = $obstacle['location']['x'];
		$x2 = $x1 + $obstacle['width'] + 1;
		$y1 = $obstacle['location']['y'];
		$y2 = $y1 + $obstacle['height'] + 1;
		$amplifiedColor = imagecolorallocate($image, 200, 200, 200);
		imagefilledrectangle($image, $x1 - $obstacleAmplification, $y1 - $obstacleAmplification, $x2 + $obstacleAmplification, $y2 + $obstacleAmplification, $amplifiedColor);
	}

	foreach ($obstacles as $obstacle) {
		$x1 = $obstacle['location']['x'];
		$x2 = $x1 + $obstacle['width'] + 1;
		$y1 = $obstacle['location']['y'];
		$y2 = $y1 + $obstacle['height'] + 1;
		$color = imagecolorallocate($image, 120, 120, 120);
		imagefilledrectangle($image, $x1, $y1, $x2, $y2, $color);
	}

	foreach ($goals as $goal) {
		$x1 = $goal['location']['x'];
		$x2 = $x1 + $goal['width'];
		$y1 = $goal['location']['y'];
		$y2 = $y1 + $goal['height'];
		$color = imagecolorallocate($image, 0, 200, 0);
		imagefilledrectangle($image, $x1, $y1, $x2, $y2, $color);
	}
}

function getColor($id) {
	$colors = [];
	$colors[0] = $id;
	$colors[1] = 120 + $id;
	$colors[2] = 255 - $colors[0];
	shuffle($colors);
	$colors = array_map(function($a) { return(substr("00".dechex($a),-2)); }, $colors);
	return implode('', $colors);
}

/* do stuff here */
$endtime = microtime(true);
echo $timediff = $endtime - $starttime;

