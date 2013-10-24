<?php

require __DIR__ . "/../../bootstrap.php";
require __DIR__ . "/../mock.php";


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

		Tester\Assert::equal("baz", $collection->baz);
		Tester\Assert::equal("1", $collection->arr1);
		Tester\Assert::equal("2", $collection->arr2);

		if ($run === 1) Tester\Assert::equal(10, $collection->foo);
		if ($run === 2) Tester\Assert::equal(15, $collection->foo);

		if ($run === 3) Tester\Assert::equal(20, $collection->foo);
		if ($run === 4) Tester\Assert::equal(25, $collection->foo);

		if ($run === 5) Tester\Assert::equal(30, $collection->foo);
		if ($run === 6) Tester\Assert::equal(35, $collection->foo);
	}

}

$reader = new \ProcessManager\Reader\ArrayReader(array(
	0 => array('foo' => 10, 'bar.foo' => 15),
	1 => array('foo' => 20, 'bar.foo' => 25),
	2 => array('foo' => 30, 'bar.foo' => 35)
));

$exector = new \ProcessManager\Executor($reader);
$exector->add(new Process());
$exector->add(new Process(), 'bar');
$exector->append("baz", "baz");
$exector->append("bar.baz", "baz");
$exector->append(array("arr1" => "1", "bar.arr1" => "1", "bar.arr2" => "2", "arr2" => "2"));
$exector->run($manager);


Tester\Assert::equal(6, $run);
