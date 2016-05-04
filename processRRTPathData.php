<?php

require_once __DIR__ . '/vendor/autoload.php';

$directory = 'C:\Users\Azathoth\Documents\Visual Studio 2015\Projects\SwarmDeployment\Win32\ReleaseNoGui\output';
//$directory = 'C:\wamp\www\UAVUtils\dubinsOptimizations';

$dataFilesRegex1 = "~distances-to-nearest-neighbour-0-05-04-16-\\d{2}-\\d{2}-\\d{2}.csv~";
$dataFilesRegex2 = "~distances-to-nearest-neighbour-1-05-04-16-\\d{2}-\\d{2}-\\d{2}.csv~";
$dataFilesRegex3 = "~distances-to-nearest-neighbour-2-05-04-16-\\d{2}-\\d{2}-\\d{2}.csv~";
$dataFilesRegexes = [$dataFilesRegex1, $dataFilesRegex2, $dataFilesRegex3];
$toGoalRegex = "~distance-to-goal-05-04-16-\\d{2}-\\d{2}-\\d{2}.csv~";

foreach ($dataFilesRegexes as $i => $dataFilesRegex) {
	$data = mergeData($dataFilesRegex, $directory);
	$outputHandle = fopen("output/experiment3/distance-to-neighbour-$i.csv", 'w+');
	foreach ($data as $index => $values) {
		fputcsv($outputHandle, $values);
	}
	fclose($outputHandle);
}


$data = mergeData($toGoalRegex, $directory);
$outputHandle = fopen("output/experiment3/distance-to-goal.csv", 'w+');
foreach ($data as $index => $values) {
	fputcsv($outputHandle, $values);
}
fclose($outputHandle);

function mergeData($regex, $directory) {
	$data = [];
	$counter = 0;
	/** @var \SplFileInfo $dataFile */
	foreach (\Nette\Utils\Finder::findFiles('*')->from($directory) as $dataFile) {
		$name = $dataFile->getBasename();
		if (!\Nette\Utils\Strings::match($name, $regex)) {
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
		$maxIterations = 174;    //zjisštěno z dat
		for ($i = $iterations + 1; $i <= $maxIterations; $i++) {
			$data[$i][$counter] = $lastValue;
		}
		fclose($handle);
		$counter++;
	}
	return $data;
}