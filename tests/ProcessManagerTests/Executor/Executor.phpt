<?php

require __DIR__ . "/../../bootstrap.php";


$run = 0;

class Process implements \ProcessManager\Process\IProcess
{

	public function getRequiredMapper()
	{
		return new \ProcessManager\Mapper();
	}

	public function execute(\ProcessManager\Collection $collection)
	{
		global $run;
		$run++;

		if ($run === 1) Tester\Assert::equal(10, $collection->foo);
		if ($run === 2) Tester\Assert::equal(10, $collection->foo);

		if ($run === 3) Tester\Assert::equal(20, $collection->foo);
		if ($run === 4) Tester\Assert::equal(20, $collection->foo);

		if ($run === 5) Tester\Assert::equal(30, $collection->foo);
		if ($run === 6) Tester\Assert::equal(30, $collection->foo);
	}

}

$reader = new \ProcessManager\Reader\ArrayReader(array(
	0 => array('foo' => 10, 'bar.foo' => 10),
	1 => array('foo' => 20, 'bar.foo' => 20),
	2 => array('foo' => 30, 'bar.foo' => 30)
));

$exector = new \ProcessManager\Executor($reader);
$exector->add(new Process());
$exector->add(new Process(), 'bar');
$exector->run();


Tester\Assert::equal(6, $run);
