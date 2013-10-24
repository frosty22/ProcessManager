<?php

require __DIR__ . "/../../bootstrap.php";
require __DIR__ . "/../mock.php";


$reader = new \ProcessManager\Reader\HtmlReader(__DIR__ . "/data/input.1.html");
$reader->setIterable('#target p', array("foo" => ""));
$reader->setKeys(array(
	"bar" 	=> 'body h1',
	"baz"	=> 'body h1:id'
));

$reader->init($manager);

Tester\Assert::exception(function() use ($reader){
	$reader->addKey('foo', 'baz');
}, 'Nette\InvalidStateException');

$i = 0;
foreach ($reader as $collection) {
	$i++;

	Tester\Assert::equal("Header bar", $collection->bar);
	Tester\Assert::equal("header_id", $collection->baz);

	if ($i === 1) Tester\Assert::equal("Foo", $collection->foo);
	if ($i === 2) Tester\Assert::equal("Bar", $collection->foo);
}

Tester\Assert::equal(2, $i);
