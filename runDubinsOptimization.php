<?php

$exeFile = 'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\SwarmDeployment.exe';
$configFile = 'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\frequencies.json';

//$frequencies = [1,2,3,4,5,6,8,10,12,14,16,18,20];
$frequencies = [16];
$count = 29;
for ($i = 0; $i < count($frequencies); $i++) {
	$frequency = $frequencies[$i];
	for ($j = 0; $j < $count; $j++) {
		$config = json_decode(file_get_contents($configFile), true);
		$config['frequencies'] = [$frequency];
		file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));
		exec(escapeshellarg($exeFile));
		echo ($j * 100 / $count) . '% of frequency ' . $frequency . ' done' . PHP_EOL;
	}
	echo "Frequency $frequency done.". PHP_EOL;
}
