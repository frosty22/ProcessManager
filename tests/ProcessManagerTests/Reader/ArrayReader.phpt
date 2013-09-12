<?php

require __DIR__ . "/../../bootstrap.php";


$reader = new \ProcessManager\Reader\ArrayReader(array(
	"foo" => 1,
	"bar" => "baz"
));

$count = 0;
foreach ($reader as $collection) {
	$count++;
	Tester\Assert::type("ProcessManager\Collection", $collection);
	Tester\Assert::equal(1, $collection->foo);
	Tester\Assert::equal("baz", $collection->bar);
}

Tester\Assert::equal(1, $count);


$count = 0;
foreach ($reader as $collection) {
	$count++;
	Tester\Assert::type("ProcessManager\Collection", $collection);
	Tester\Assert::equal(1, $collection->foo);
	Tester\Assert::equal("baz", $collection->bar);
}

Tester\Assert::equal(1, $count);


$arr = array();
$arr[] = array("foo" => 1);
$arr[] = array("bar" => 2);

$reader = new \ProcessManager\Reader\ArrayReader($arr);

$count = 0;
foreach ($reader as $collection) {
	$count++;
	Tester\Assert::type("ProcessManager\Collection", $collection);
	if ($count === 1) Tester\Assert::equal(1, $collection->foo);
	if ($count === 2) Tester\Assert::equal(2, $collection->bar);
}

Tester\Assert::equal(2, $count);
