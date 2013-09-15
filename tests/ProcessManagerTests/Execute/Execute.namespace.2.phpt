<?php

require __DIR__ . "/../../bootstrap.php";


class SecondProcess implements \ProcessManager\Process\IProcess
{

	public function getRequiredMapper()
	{
		$mapper = new \ProcessManager\Mapper();
		$mapper->addText("name")->setRequired(TRUE);
		return $mapper;
	}

	public function execute(\ProcessManager\Collection $collection)
	{
		return $collection->name . "!";
	}

}



$collection = new \ProcessManager\Collection(array(
	'sub.sub.user.name' => 'Jan'
));

$execute = new \ProcessManager\Execute(new SecondProcess(), 'sub.sub.user');
$execute->addTarget('foo.bar');
$execute->run($collection);

Tester\Assert::equal("Jan!", $collection->foo->bar);




