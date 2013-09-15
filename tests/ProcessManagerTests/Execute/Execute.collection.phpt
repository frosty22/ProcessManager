<?php

require __DIR__ . "/../../bootstrap.php";


class Process implements \ProcessManager\Process\IProcess
{

	public function getRequiredMapper()
	{
		$mapper = new \ProcessManager\Mapper();
		$mapper->addText("name")
			->setRequired(TRUE);
		return $mapper;
	}

	public function execute(\ProcessManager\Collection $collection)
	{
		return $collection->name . "!";
	}

}



Tester\Assert::exception(function(){
	$execute = new \ProcessManager\Execute(new Process());
	$execute->run(new \ProcessManager\Collection());
}, 'ProcessManager\MissingKeyException');



Tester\Assert::exception(function(){
	$execute = new \ProcessManager\Execute(new Process(), 'bar');
	$execute->run(new \ProcessManager\Collection());
}, 'ProcessManager\InvalidArgumentException');



$collection = new \ProcessManager\Collection(array(
	'name' => 'bar'
));

$execute = new \ProcessManager\Execute(new Process());
$execute->addTarget('foo');
$execute->run($collection);

Tester\Assert::equal("bar!", $collection->foo);




