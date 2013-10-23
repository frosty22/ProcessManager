<?php

require __DIR__ . "/../../bootstrap.php";


$reader = new \ProcessManager\Reader\NullableReader();

$i = 0;
foreach ($reader as $collection) {
	$i++;
	Tester\Assert::type('ProcessManager\Collection', $collection);
}


Tester\Assert::equal(1, $i);