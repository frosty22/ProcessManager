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
$execute->addCondition('cond');
$execute->addTarget('foo');
$execute->run($collection);
Tester\Assert::equal(12345, $collection->foo);


$collection = new \ProcessManager\Collection(array('cond' => 123));
$execute = new \ProcessManager\Execute(new Process());
$execute->addCondition('cond');
$execute->addTarget('foo');
$execute->run($collection);
Tester\Assert::null($collection->foo);


$collection = new \ProcessManager\Collection(array('cond' => 123, 'bar' => 234));
$execute = new \ProcessManager\Execute(new Process());
$execute->addCondition('cond', 123);
$execute->addCondition('bar', 234);
$execute->addTarget('foo');
$execute->run($collection);
Tester\Assert::equal(12345, $collection->foo);


$collection = new \ProcessManager\Collection(array('foo' => 1, 'bar' => 1, 'baz' => 1));
$execute = new \ProcessManager\Execute(new Process());
$execute->addCondition($execute::TARGET, 1);
$execute->addTarget('foo');
$execute->addTarget('bar');
$execute->addTarget('baz');
$execute->run($collection);
Tester\Assert::equal(12345, $collection->foo);

