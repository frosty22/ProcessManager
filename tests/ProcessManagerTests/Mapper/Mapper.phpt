<?php

require __DIR__ . "/../../bootstrap.php";

$mapper = new \ProcessManager\Mapper();
$mapper->addText("foo");
$mapper->addInteger("bar");
$mapper->addText("baz");

Tester\Assert::type('Iterator', $mapper);

for ($j = 0; $j <= 1; $j++) {

	$i = 0;
	foreach ($mapper as $type) {

		if ($i === 0)
			Tester\Assert::type('ProcessManager\Type\Text', $type);

		if ($i === 1)
			Tester\Assert::type('ProcessManager\Type\Integer', $type);

		if ($i === 2)
			Tester\Assert::type('ProcessManager\Type\Text', $type);

		$i++;
	}

}