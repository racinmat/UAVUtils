<?php
/**
 * Created by PhpStorm.
 * User: Azathoth
 * Date: 5. 3. 2016
 * Time: 22:42
 */

use \Nette\Utils\Strings;

require_once 'vendor/autoload.php';

$data = file_get_contents('dump2.txt');
$lines = Strings::split($data, "~\n~");
$numbers = [];
foreach ($lines as $line) {
	if (Strings::contains($line, 'creating state ')) {
		$number = Strings::substring($line, Strings::length('creating state '));
		$numbers[$number] = $number;
		echo  count($numbers) . PHP_EOL;
	} else if(Strings::contains($line, 'destroying state ')) {
		$number = Strings::substring($line, Strings::length('destroying state '));
		unset($numbers[$number]);
	}
}
echo PHP_EOL;
var_dump($numbers);