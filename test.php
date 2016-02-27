<?php

class UAV {
	/** @var double */
	public $x;
	/** @var double */
	public $y;
	/** @var double */
	public $phi;

	/**
	 * UAV constructor.
	 * @param float $x
	 * @param float $y
	 * @param float $phi
	 */
	public function __construct($x, $y, $phi)
	{
		$this->x = $x;
		$this->y = $y;
		$this->phi = $phi;
	}

	function __toString()
	{
//		return "x: " . $this->x . ", y: " . $this->y . ", angle: " . $this->phi . PHP_EOL;
		return "" . $this->x . ", " . $this->y . ", angle: " . $this->phi . PHP_EOL;
	}

}

class Control {
	/** @var double */
	public $step;
	/** @var double */
	public $curvature;

	/**
	 * Control constructor.
	 * @param float $step
	 * @param float $curvature
	 */
	public function __construct($step, $curvature)
	{
		$this->step = $step;
		$this->curvature = $curvature;
	}

}

function newSystem($uavZ, $curvature) {
	$v = 30;//velocity
	$K = $curvature;//curvature
	$w = 4;//ascentVelocity
	$timeStep = 0.5;

	$phi = $uavZ;

	if($K == 0)
	{
		$diffX = $v * cos((float) $phi) * $timeStep;
		$diffY = $v * sin((float) $phi) * $timeStep;
	} else
	{
		$diffX = (1 / $K) * (sin((float) ($phi + $K * $v * $timeStep)) - sin((float) $phi));
		$diffY = - (1 / $K) * (cos((float) ($phi + $K * $v * $timeStep)) - cos((float) $phi));
	}

	echo $diffX . ", " . $diffY . PHP_EOL;

	$diffZ = $K * $v * $timeStep;
	return [$diffX, $diffY, $uavZ + $diffZ];
}


function calculateNewState(UAV $uav, Control $control) {
	$newCoords = newSystem($uav->phi, $control->curvature);
	return new UAV($uav->x + $newCoords[0], $uav->y + $newCoords[1], $newCoords[2]);
}

//echo $curvature2 = 0.02 . PHP_EOL;
//
//echo "new system 4 steps:";
//$step1 = newSystem((float) 0, $curvature2);
//$step2 = newSystem((float) $step1[2], $curvature2);
//$step3 = newSystem((float) $step2[2], $curvature2);
//$step4 = newSystem((float) $step3[2], $curvature2);
//echo "step 1: " . PHP_EOL . $step1[0] . ', ' . $step1[1] . ", angle: " . $step1[2] . PHP_EOL;
//echo "step 2: " . PHP_EOL . ($step1[0] + $step2[0]) . ', ' . ($step1[1] + $step2[1]) . ", angle: " . $step2[2] . PHP_EOL;
//echo "step 3: " . PHP_EOL . ($step1[0] + $step2[0] + $step3[0]) . ', ' . ($step1[1] + $step2[1] + $step3[1]) . ", angle: " . $step3[2] . PHP_EOL;
//echo "step 4: " . PHP_EOL . ($step1[0] + $step2[0] + $step3[0] + $step4[0]) . ', ' . ($step1[1] + $step2[1] + $step3[1] + $step4[1]) . ", angle: " . $step4[2] . PHP_EOL;

//$uav = new UAV(80, 50, pi()/2);
//echo $uav;
//echo $uav2 = calculateNewState($uav, new Control(20, - $curvature2));
//echo calculateNewState($uav2, new Control(20, - $curvature2));
////echo calculateNewState($uav, new Control(20, $curvature2));
//
//echo "curvature = " . $curvature2;
//echo "radius = " . 1/$curvature2;

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
//	'path-02-27-23-35-16.json',
	'path-02-27-23-35-16-resampled.json'
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


function drawPaths($path, $name) {
	$image = imagecreatetruecolor(800, 800);
	$black = imagecolorallocate($image, 0, 0, 0);
	$white = imagecolorallocate($image, 255, 255, 255);
	imagefill($image, 0, 0, $white);

	$previous = array_shift($path);

	imagepng($image, $name . '.png');
}


