<?php

require_once __DIR__ . '/vendor/autoload.php';

$directory = 'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\output';
//$directory = 'C:\wamp\www\UAVUtils\dubinsOptimizations';

//$frequencies = [1,2,3,4,5,6,8,10,12,14,16,18,20];
$frequencies = [12,14,16];
foreach ($frequencies as $frequency) {
	$dataFilesRegex = "~dubinsResult-04-(16|17)-16-\\d{2}-\\d{2}-\\d{2}-{$frequency}Hz.csv~";
	$data = [];
	$maxIterations = 0;

	//zjistit maximální počet iterací (maxindex)
	/** @var \SplFileInfo $dataFile */
	foreach (\Nette\Utils\Finder::findFiles('*')->from($directory) as $dataFile) {
		$name = $dataFile->getBasename();
		if (!\Nette\Utils\Strings::match($name, $dataFilesRegex)) {
			continue;
		}
		$handle = fopen($dataFile->getRealPath(), "r");
		while(($row = fgetcsv($handle, null, ';')) !== FALSE ) {
			$index = $row[0];
			if ($index > $maxIterations) {
				$maxIterations = $index;
			}
		}
		fclose($handle);
	}

	$counter = 0;
	/** @var \SplFileInfo $dataFile */
	foreach (\Nette\Utils\Finder::findFiles('*')->from($directory) as $dataFile) {
		$name = $dataFile->getBasename();
		if (!\Nette\Utils\Strings::match($name, $dataFilesRegex)) {
			continue;
		}
		$handle = fopen($dataFile->getRealPath(), "r");
		$iterations = 0;
		$lastValue = 0;
		while(($row = fgetcsv($handle, null, ';')) !== FALSE ) {
			$index = $row[0];
			$value = $row[1];
			if (!isset($data[$index])) {
				$data[$index] = [];
			}
			$data[$index][$counter] = (float) $value;
			$iterations = $index;
			$lastValue = (float) $value;
		}

//		echo count(array_column($data, $counter)) . PHP_EOL;
		for ($i = $iterations + 1; $i <= $maxIterations; $i++) {
			$data[$i][$counter] = $lastValue;
		}
		fclose($handle);
		$counter++;
	}


	$outputHandle = fopen("output/$frequency Hz stats.csv", 'w+');
	foreach ($data as $index => $values) {
		fputcsv($outputHandle, $values);
	}
	fclose($outputHandle);

}
