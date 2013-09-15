<?php

require __DIR__ . "/../../bootstrap.php";


$run = 0;

class FooProcess implements \ProcessManager\Process\IProcess
{

	public function getRequiredMapper()
	{
		$mapper = new \ProcessManager\Mapper();

		$mapper->addText("Foo");

		$mapper->addObject("time", "DateTime");

		$mapper->addObject("requiredTime", "DateTime")
			->setRequired();

		return $mapper;
	}

	public function execute(\ProcessManager\Collection $collection)
	{
		global $run;
		$run++;

		if ($run === 1) {
			Tester\Assert::equal('some', $collection->Foo);
			Tester\Assert::equal('2012', $collection->requiredTime->format("Y"));
			Tester\Assert::null($collection->time);
		}

		if ($run === 2) {
			Tester\Assert::equal('any', $collection->Foo);
			Tester\Assert::equal('2011', $collection->requiredTime->format("Y"));
			Tester\Assert::equal('2010', $collection->time->format("Y"));
		}
	}

}


$reader = new \ProcessManager\Reader\ArrayReader(array(
	array(
		'requiredTime' => '2012-10-10',
		'Foo'	=> 'some'),
	array(
		'Foo' => 'any',
		'requiredTime' => '2011-01-01 00:00:10',
		'time' => '2010-02-02 02:02:10'
	)
));


$executor = new \ProcessManager\Executor($reader);
$executor->add(new FooProcess());

// Run it
$manager = new \ProcessManager\ProcessManager();
$manager->onBeforeProcessCheck[] = function(\ProcessManager\Process\IProcess $process, \ProcessManager\Collection $collection) {
	$listener = new \ProcessManager\Listener\DateTimeConvertListener();
	$listener->onBeforeProcessCheck($process, $collection);
};
$manager->execute($executor);


Tester\Assert::equal(2, $run);