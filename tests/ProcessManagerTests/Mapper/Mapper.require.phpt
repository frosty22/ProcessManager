<?php

require __DIR__ . "/../../bootstrap.php";

$mapper = new \ProcessManager\Mapper();

$mapper->addText("text1");

$mapper->addText("text2")
			->setRequired(FALSE);

$mapper->addText("text3")
			->setRequired(TRUE);

Tester\Assert::exception(function() use($mapper){
	$collection = new \ProcessManager\Collection();
	$mapper->check($collection);
}, 'ProcessManager\MissingKeyException');

$collection = new \ProcessManager\Collection();
$collection->text1 = "bar";
$collection->text3 = "foo";
Tester\Assert::equal($collection, $mapper->check($collection));