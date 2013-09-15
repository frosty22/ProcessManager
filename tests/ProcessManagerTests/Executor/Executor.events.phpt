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
		Tester\Assert::equal('bar', $collection->foo);
	}

}

$called = (object)array(
	'onReadCollection'	=> 0,
	'onBeforeCheck'		=> 0,
	'onBeforeExecute'	=> 0,
	'onAfterExecute'	=> 0
);

$reader = new \ProcessManager\Reader\ArrayReader(array(
	0 => array('foo' => 'bar'),
	1 => array('foo' => 'bar')
));

$executor = new \ProcessManager\Executor($reader);
$executor->onReadCollection[] = function(\ProcessManager\Reader\IReader $reader, \ProcessManager\Collection $collection)
									use ($called) {
	$called->onReadCollection++;
};


$executor->onBeforeProcessCheck[] = function(\ProcessManager\Process\IProcess $process,
									 \ProcessManager\Collection $collection) use ($called) {
	$called->onBeforeCheck++;
};

$executor->onBeforeProcessExecute[] = function(\ProcessManager\Process\IProcess $process,
									   \ProcessManager\Collection $collection) use ($called) {
	$called->onBeforeExecute++;
};

$executor->onAfterProcessExecute[] = function(\ProcessManager\Process\IProcess $process,
									  \ProcessManager\Collection $collection) use ($called) {
	$called->onAfterExecute++;
};

$executor->add(new Process());
$executor->run();


Tester\Assert::equal(2, $called->onReadCollection);
Tester\Assert::equal(2, $called->onBeforeCheck);
Tester\Assert::equal(2, $called->onBeforeExecute);
Tester\Assert::equal(2, $called->onAfterExecute);

Tester\Assert::equal(2, $run);