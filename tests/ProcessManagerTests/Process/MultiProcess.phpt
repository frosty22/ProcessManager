<?php

require __DIR__ . "/../../bootstrap.php";


class FooProcess implements \ProcessManager\Process\IProcess
{

	public function getRequiredMapper() {
		$mapper = new \ProcessManager\Mapper();
		$mapper->addText("Foo");
		return $mapper;
	}


	public function execute(\ProcessManager\Collection $collection) {
	}

}


// TODO: Mock doesnt work - error in type-hinting of Mockista probably
//$mock = new \Mockista\MockBuilder('ProcessManager\ProcessManager');
//$manager = $mock->getMock();
$manager = new ProcessManager\ProcessManager();


$process = new \ProcessManager\Process\MultiProcess($manager);
$process->addProcess(new FooProcess());
$process->addProcess(new FooProcess(), "collect");
$process->addProcess(new FooProcess(), "collect2");


$i = 0;
$mapper = $process->getRequiredMapper();
foreach ($mapper as $type) {
	$i++;
	Tester\Assert::type('ProcessManager\Type\Object', $type);
}

Tester\Assert::equal(2, $i);

Tester\Assert::exception(function() use($process) {
	$process->execute(new \ProcessManager\Collection());
}, 'ProcessManager\InvalidArgumentException');

