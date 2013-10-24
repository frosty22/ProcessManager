<?php

require __DIR__ . "/../../bootstrap.php";
require __DIR__ . "/../mock.php";


$reader = new \ProcessManager\Reader\NullableReader();
$reader->init($manager);

$i = 0;
foreach ($reader as $collection) {
	$i++;
	Tester\Assert::type('ProcessManager\Collection', $collection);
}


Tester\Assert::equal(1, $i);