<?php

require __DIR__ . "/../../bootstrap.php";

$mapper = new \ProcessManager\Mapper();
$mapper->addText("foo");

Tester\Assert::throws(function()use($mapper){
	$collection = new \ProcessManager\Collection();
	$collection->foo = array();
	$mapper->check($collection);
}, 'ProcessManager\InvalidValueException');

