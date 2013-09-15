<?php

require __DIR__ . "/../../bootstrap.php";


class Process implements \ProcessManager\Process\IProcess
{

	public function getRequiredMapper()
	{
		return new \ProcessManager\Mapper();
	}

	public function execute(\ProcessManager\Collection $collection)
	{
		return 12345;
	}

}


$collection = new \ProcessManager\Collection();
$execute = new \ProcessManager\Execute(new Process());
$execute->run($collection);
Tester\Assert::equal(new \ProcessManager\Collection(), $collection);


$collection = new \ProcessManager\Collection();
$execute = new \ProcessManager\Execute(new Process());
$execute->addTarget('foo');
$execute->addTarget('bar.baz');
$execute->run($collection);
Tester\Assert::equal(12345, $collection->foo);
Tester\Assert::equal(12345, $collection->bar->baz);


