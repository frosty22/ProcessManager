<?php

require __DIR__ . "/../../bootstrap.php";


$reader = new \ProcessManager\Reader\XmlReader(__DIR__ . "/data/input.2.xml");
$reader->setKeys(array(
	'name'				=> 'name',
	'genre'				=> 'genre',
	'link'				=> 'web.link',
	'web'				=> 'web.name',
	'id'				=> 'name:id',
	'secure'			=> 'web.link:https'
));


$i = 0;
foreach ($reader as $collection) {
	$i++;

	Tester\Assert::equal('Jan', $collection->name);
	Tester\Assert::equal('man', $collection->genre);
	Tester\Assert::equal('google.com', $collection->link);
	Tester\Assert::equal('Google', $collection->web);
	Tester\Assert::equal('123', $collection->id);
	Tester\Assert::equal('no', $collection->secure);
}

Tester\Assert::equal(1, $i);

