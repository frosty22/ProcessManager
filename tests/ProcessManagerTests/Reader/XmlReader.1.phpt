<?php

require __DIR__ . "/../../bootstrap.php";
require __DIR__ . "/../mock.php";


$reader = new \ProcessManager\Reader\XmlReader(__DIR__ . "/data/input.1.xml");
$reader->setIterable('items', 'item');
$reader->setKeys(array(
	'name'				=> 'name',
	'genre'				=> 'genre',
	'link'				=> 'web.link',
	'web'				=> 'web.name',
	'itemName'			=> 'items.item.name',
	'itemAge'			=> 'items.item.age',
	'empty'				=> 'unexists',
	'empty2'			=> 'web.unexists',
	'empty3'			=> 'foo.bar'
));

$reader->init($manager);

Tester\Assert::exception(function() use ($reader){
	$reader->addKey('foo', 'baz');
}, 'Nette\InvalidStateException');


$i = 0;
foreach ($reader as $collection) {
	$i++;

	Tester\Assert::equal('Jan', $collection->name);
	Tester\Assert::equal('man', $collection->genre);
	Tester\Assert::equal('google.com', $collection->link);
	Tester\Assert::equal('Google', $collection->web);
	Tester\Assert::null($collection->empty);
	Tester\Assert::null($collection->empty2);
	Tester\Assert::null($collection->empty3);

	if ($i === 1) {
		Tester\Assert::equal('First', $collection->itemName);
		Tester\Assert::equal('2', $collection->itemAge);
	}

	if ($i === 2) {
		Tester\Assert::equal('Second', $collection->itemName);
		Tester\Assert::equal('3', $collection->itemAge);
	}

}

Tester\Assert::equal(2, $i);

