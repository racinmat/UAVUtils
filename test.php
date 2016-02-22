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
		return "x: " . $this->x . ", y: " . $this->y . ", angle: " . $this->phi . PHP_EOL;
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

function oldSystem($uavZ, $curvature) {
	$uav_size = 0.5;
	$time_step = 0.05;
	$end_time = 0.5;

	$control = new \stdClass();
	$control->step = 30;
	$control->turn = $curvature;

	$dPhi = ($control->step / $uav_size) * tan((float) $control->turn);	//dPhi se nemění v rámci vnitřního cyklu, takže stačí spošítat jen jednou

	$x = 0;
	$y = 0;

	for ($i = $time_step; $i < $end_time; $i += $time_step)
	{
		//calculate derivatives from inputs
		$dx = $control->step * cos((float) $uavZ);	//pokud jsme ve 2D, pak jediná možná rotace je rotace okolo osy Z
		$dy = $control->step * sin((float) $uavZ);	//input není klasický bod se souřadnicemi X, Y, ale objekt se dvěma čísly, odpovídajícími dvěma vstupům do car_like modelu

		//calculate current state variables
		$x += $dx * $time_step;
		$y += $dy * $time_step;
		$uavZ += $dPhi * $time_step;
	}
	return [$x, $y, $uavZ];
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

//echo "phi :" . PHP_EOL;
//echo $phi = 0 . PHP_EOL;
//echo "old curvature :" . PHP_EOL;
echo $curvature1 = pi()/150 . PHP_EOL;
//echo "new curvature :" . PHP_EOL;
echo $curvature2 = 2 * tan((float) $curvature1) . PHP_EOL;
//echo "old system" . PHP_EOL;
//echo implode(', ', oldSystem($phi, $curvature1)) . PHP_EOL;
//echo "new system" . PHP_EOL;
//echo implode(', ', newSystem($phi, $curvature2)) . PHP_EOL;
//
//echo "old system 4 steps:";
//$step1 = oldSystem((float) 0, $curvature1);
//$step2 = oldSystem((float) $step1[2], $curvature1);
//$step3 = oldSystem((float) $step2[2], $curvature1);
//$step4 = oldSystem((float) $step3[2], $curvature1);
//echo "step 1: " . PHP_EOL . $step1[0] . ', ' . $step1[1] . PHP_EOL;
//echo "step 2: " . PHP_EOL . ($step1[0] + $step2[0]) . ', ' . ($step1[1] + $step2[1]) . PHP_EOL;
//echo "step 3: " . PHP_EOL . ($step1[0] + $step2[0] + $step3[0]) . ', ' . ($step1[1] + $step2[1] + $step3[1]) . PHP_EOL;
//echo "step 4: " . PHP_EOL . ($step1[0] + $step2[0] + $step3[0] + $step4[0]) . ', ' . ($step1[1] + $step2[1] + $step3[1] + $step4[1]) . PHP_EOL;
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

$uav = new UAV(80, 50, pi()/2);
echo $uav;
echo calculateNewState($uav, new Control(20, - $curvature2));
echo calculateNewState($uav, new Control(20, 0));
echo calculateNewState($uav, new Control(20, $curvature2));
