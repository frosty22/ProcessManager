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
		return $collection->name . '!';
	}

}


$collection = new \ProcessManager\Collection(array(
	'user.name' 	=> 'Jan',
	'user.genre'	=> 'man'
));

$execute = new \ProcessManager\Execute(new Process(), 'user');
$execute->addTarget('user');

$called = (object)array(
	'onBeforeCheck' 	=> 0,
	'onBeforeExecute'	=> 0,
	'onAfterExecute'	=> 0
);

$execute->onBeforeCheck[] = function(\ProcessManager\Process\IProcess $process,
									 \ProcessManager\Collection $collection) use ($called) {
	$called->onBeforeCheck++;
	Tester\Assert::equal(new Process(), $process);
	Tester\Assert::equal('Jan', $collection->name);
	$collection->name = 'Honza';
};

$execute->onBeforeExecute[] = function(\ProcessManager\Process\IProcess $process,
									 \ProcessManager\Collection $collection) use ($called) {
	$called->onBeforeExecute++;
	Tester\Assert::equal(new Process(), $process);
	Tester\Assert::equal('Honza', $collection->name);
};

$execute->onAfterExecute[] = function(\ProcessManager\Process\IProcess $process,
									   \ProcessManager\Collection $collection) use ($called) {
	$called->onAfterExecute++;
	Tester\Assert::equal(new Process(), $process);
	Tester\Assert::equal('Honza!', $collection->user);
};

$execute->run($collection);


Tester\Assert::equal(1, $called->onBeforeCheck);
Tester\Assert::equal(1, $called->onBeforeExecute);
Tester\Assert::equal(1, $called->onAfterExecute);
