<?php

/*
 *   Get the number of lines in the provided file
 */
function getNumberOfValues($filename) {

	$file = fopen($filename, 'r');
	$count = 0;

	while (!feof($file)) {
		fgets($file);
		$count++;
	}

	fclose($file);

	return $count;
}


/*
 *   Searching for a value with a key provided using the binary search algorythm
 */
function binarySearchByKey($filename, $key_value) {

	$mem_start = memory_get_usage();

	$s = 0;	// start point
	$f = getNumberOfValues($filename);  // end point

	$file = new SplFileObject($filename);

	while ($s <= $f) {

		$half = floor(($s + $f) / 2);

		$file->seek($half);	// moving to a particular line

		$line = explode("\t", $file->current());  // getting a key and value separated

		// key values are supposed to be in lexicographical order, so strnatcmp is used for corparison
		$comparison = strnatcmp($line[0], $key_value);

		if ($comparison > 0) {
			$f = $half - 1;
		} else if ($comparison < 0) {
			$s = $half + 1;
		} else {
			echo "<span style='background-color: #F5FCAC;'>Используемая память:</span> " . (memory_get_usage() - $mem_start) . " байт<br>";
			return $line[1];
		}

	}
	echo "<span style='background-color: #F5FCAC;'>Используемая память: </span>" . (memory_get_usage() - $mem_start) . "<br>";
	
	// no matches :(
	return 'undef';		
}

$start = microtime(true);

if (isset($_GET['key'])) {

	$key_value = $_GET['key'];

	echo "<span style='background-color: #ACF0FC;'>Полученное значение по ключу:</span>  " . binarySearchByKey('data.txt', $key_value);
	echo "<br><span style='background-color: #FCACAC;'>Скрипт выполнен за:</span>  " . (microtime(true) - $start) . " секунд";

} else {

	echo "Используйте URL типа: <span style='background-color: #ACF0FC;'>ваш.домен/?key=имя_ключа</span>";

}


?>