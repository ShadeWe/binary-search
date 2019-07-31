<?php

$start = microtime(true);

function binarySearchByKey($filename, $key_value) {

	$data = file_get_contents($filename);

	$pieces = explode("\x0A", $data);

	$keyValueArray = array();

	foreach($pieces as $key => $value) {
		$keyValueArray[] = explode("\t", $value);
	}

	$s = 0;	// start point
	$f = count($keyValueArray) - 1;  // end point

	while ($s <= $f) {

		$half = floor(($s + $f) / 2);

		// key values are supposed to be in lexicographical order, so strnatcmp is used for corparison
		$comparison = strnatcmp($keyValueArray[$half][0], $key_value);

		if ($comparison > 0) {
			$f = $half - 1;
		} else if ($comparison < 0) {
			$s = $half + 1;
		} else {
			return $keyValueArray[$half][1];
		}

		return 'undef';
	}
		
}
if (isset($_GET['key'])) {

	$key_value = $_GET['key'];

	echo "<span style='background-color: #ACF0FC;'>Полученное значение по ключу:</span>  " . binarySearchByKey('data.txt', $key_value);
	echo "<br><span style='background-color: #FCACAC;'>Скрипт выполнен за:</span>  " . (microtime(true) - $start);

} else {

	echo "Используйте URL типа: <span style='background-color: #ACF0FC;'>ваш.домен/?key=имя_ключа</span>";

}


