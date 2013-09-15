<?php

require __DIR__ . "/../../bootstrap.php";


class Process implements \ProcessManager\Process\IProcess
{

	public function getRequiredMapper()
	{
		$mapper = new \ProcessManager\Mapper();
		$mapper->addText("firstName")->setRequired(TRUE);
		$mapper->addText("lastName")->setRequired(TRUE);
		return $mapper;
	}

	public function execute(\ProcessManager\Collection $collection)
	{
		return $collection->firstName . " " . $collection->lastName;
	}

}



$collection = new \ProcessManager\Collection(array(
	'user.firstName' => 'Jan',
	'user.lastName'	=> 'Novak'
));

$execute = new \ProcessManager\Execute(new Process(), 'user');
$execute->addTarget('name');
$execute->run($collection);

Tester\Assert::equal("Jan Novak", $collection->name);




