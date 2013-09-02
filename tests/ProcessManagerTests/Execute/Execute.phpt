<?php

require __DIR__ . "/../../bootstrap.php";


class FooProcess implements \ProcessManager\Process\IProcess
{

	public function getRequiredMapper()
	{ }

	public function execute(\ProcessManager\Collection $collection)
	{ }

}



$execute = new \ProcessManager\Execute(new FooProcess(), "bar");
Tester\Assert::type("FooProcess", $execute->getProcess());
Tester\Assert::equal("bar", $execute->getNamespace());


$execute = new \ProcessManager\Execute(new FooProcess());
Tester\Assert::type("FooProcess", $execute->getProcess());
Tester\Assert::null($execute->getNamespace());
