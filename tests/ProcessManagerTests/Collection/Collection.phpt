<?php

require __DIR__ . "/../../bootstrap.php";


$collection = new \ProcessManager\Collection();
$collection->foo = "bar";

Tester\Assert::equal("bar", $collection->foo);
Tester\Assert::null($collection->foo2);

Tester\Assert::false($collection->isChecked());

$collection->bar = array();

$collection->baz = FALSE;
Tester\Assert::false($collection->baz);
unset($collection->baz);

Tester\Assert::null($collection->baz);

$collection[0] = "ha";
Tester\Assert::equal("ha", $collection[0]);
unset($collection[0]);

Tester\Assert::null($collection[0]);


Tester\Assert::false($collection->exist("foo2"));
Tester\Assert::true($collection->exist("foo"));

Tester\Assert::false(isset($collection->foo2));
Tester\Assert::true(isset($collection->foo));

Tester\Assert::exception(function() use($collection) {
	$collection[array()] = true;
}, 'ProcessManager\InvalidArgumentException');

$collection->setChecked();

Tester\Assert::exception(function() use($collection) {
	$collection->foo = true;
}, 'ProcessManager\InvalidStateException');


