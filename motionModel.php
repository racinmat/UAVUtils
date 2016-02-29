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


function newSystem($uavZ, Control $control) {
	$v = $control->step;//velocity
	$K = $control->curvature;//curvature
	$w = 4;//ascentVelocity
	$timeStep = 1;

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
//
//	echo $diffX . ", " . $diffY . PHP_EOL;

	$diffZ = $K * $v * $timeStep;
	return [$diffX, $diffY, $uavZ + $diffZ];
}


function calculateNewState(UAV $uav, Control $control) {
	$newCoords = newSystem($uav->phi, $control);
	return new UAV($uav->x + $newCoords[0], $uav->y + $newCoords[1], $newCoords[2]);
}

$curvature = 0.02;

//echo "new system 4 steps:";
//$step1 = newSystem((float) 0, $curvature);
//$step2 = newSystem((float) $step1[2], $curvature);
//$step3 = newSystem((float) $step2[2], $curvature);
//$step4 = newSystem((float) $step3[2], $curvature);
//echo "step 1: " . PHP_EOL . $step1[0] . ', ' . $step1[1] . ", angle: " . $step1[2] . PHP_EOL;
//echo "step 2: " . PHP_EOL . ($step1[0] + $step2[0]) . ', ' . ($step1[1] + $step2[1]) . ", angle: " . $step2[2] . PHP_EOL;
//echo "step 3: " . PHP_EOL . ($step1[0] + $step2[0] + $step3[0]) . ', ' . ($step1[1] + $step2[1] + $step3[1]) . ", angle: " . $step3[2] . PHP_EOL;
//echo "step 4: " . PHP_EOL . ($step1[0] + $step2[0] + $step3[0] + $step4[0]) . ', ' . ($step1[1] + $step2[1] + $step3[1] + $step4[1]) . ", angle: " . $step4[2] . PHP_EOL;

$uav = new UAV(450, 50, 1.5707963267948966);
echo $uav;
echo $uav = calculateNewState($uav, new Control(20/70, - $curvature));
echo $uav = calculateNewState($uav, new Control(20/70, - $curvature));
for ($i = 0; $i < 20; $i++) {
	$uav = calculateNewState($uav, new Control(20/70, - $curvature));
}
echo $uav;
//echo calculateNewState($uav, new Control(20, $curvature));

echo "curvature = " . $curvature;
echo "radius = " . 1/$curvature;
