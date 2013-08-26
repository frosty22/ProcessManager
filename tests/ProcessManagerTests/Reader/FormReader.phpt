<?php

require __DIR__ . "/../../bootstrap.php";


$mock = new \Mockista\MockBuilder('Nette\Forms\Form');
$mock->getValues(TRUE)
	->once()
	->andReturn(array(
		"foo" => "bar",
		"bar" => array(1, 2),
		"baz" => new stdClass(),
		"toRename" => "baz",
		"toRemove" => TRUE,
		"key" => "value",
		"copy1" => "copied"
));

$form = $mock->getMock();



$reader = new \ProcessManager\Reader\FormReader($form);
$reader->rename("toRename", "renamed");
$reader->remove("toRemove");
$reader->copy("copy1", "copy2");

$reader->rename("key", "key.subkey");

$count = 0;
foreach ($reader as $collection) {
	$count++;
	Tester\Assert::equal("bar", $collection->foo);
	Tester\Assert::equal(array(1, 2), $collection->bar);
	Tester\Assert::equal(new stdClass(), $collection->baz);
	Tester\Assert::equal("baz", $collection->renamed);
	Tester\Assert::null($collection->toRename);
	Tester\Assert::null($collection->toRemove);
	Tester\Assert::equal("value", $collection->key->subkey);
	Tester\Assert::equal("copied", $collection->copy1);
	Tester\Assert::equal("copied", $collection->copy2);
}

Tester\Assert::equal(1, $count);
Tester\Assert::equal($collection, $reader->getCollection());


Tester\Assert::exception(function() use($reader) {
	$reader->rename("bar", "baz");
}, "Nette\InvalidStateException");

Tester\Assert::exception(function() use($reader) {
	$reader->remove("bar");
}, "Nette\InvalidStateException");

