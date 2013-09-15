<?php

require __DIR__ . "/../../bootstrap.php";


class Process implements \ProcessManager\Process\IProcess
{

	public function getRequiredMapper()
	{
	}

	public function execute(\ProcessManager\Collection $collection)
	{
	}

}

Tester\Assert::exception(function(){
		new \ProcessManager\Execute(new Process());
}, 'ProcessManager\InvalidArgumentException');

