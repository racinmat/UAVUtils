<?php

function runDubinsOptimization(array $frequencies, $count, $pathFile = null) {

	//original json used for optimizations: C:\\Users\\Azathoth\\Documents\\Visual Studio 2015\\Projects\\SwarmDeployment\\Win32\\Release\\output\\path-03-27-18-09-16-before-dubins.json
	$exeFile = 'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\SwarmDeployment.exe';
	$configFile = 'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\frequencies.json';

//	$frequencies = [$frequency];
	for ($i = 0; $i < count($frequencies); $i++) {
		$frequency = $frequencies[$i];
		for ($j = 0; $j < $count; $j++) {
			$config = json_decode(file_get_contents($configFile), true);
			$config['frequencies'] = [$frequency];
			if ($pathFile) {
				$config['path'] = $pathFile;
			}
			file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));
			exec(escapeshellarg($exeFile));
			echo ($j * 100 / $count) . '% of frequency ' . $frequency . ' done' . PHP_EOL;
		}
		echo "Frequency $frequency done.". PHP_EOL;
		sleep(1);
	}

}

runDubinsOptimization([12,14,16,18,20], 33, 'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\Release\output\path-03-27-17-49-16-before-dubins.json');
