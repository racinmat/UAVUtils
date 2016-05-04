<?php

function runRRTPath($count) {

	//original json used for optimizations: C:\\Users\\Azathoth\\Documents\\Visual Studio 2015\\Projects\\SwarmDeployment\\Win32\\Release\\output\\path-03-27-18-09-16-before-dubins.json
	$exeFile = 'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\SwarmDeployment.exe';

	for ($j = 0; $j < $count; $j++) {
		exec(escapeshellarg($exeFile));
		echo ($j * 100 / $count) . '% of frequency done' . PHP_EOL;
//		sleep(1);
	}

}

runRRTPath(250);
