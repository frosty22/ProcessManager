<?php

require __DIR__ . "/../../bootstrap.php";


$mock = new \Mockista\MockBuilder('ProcessManager\Reader\FormReader');
$reader = $mock->getMock();


$exector = new \ProcessManager\Executor($reader);


$mock = new \Mockista\MockBuilder('ProcessManager\Process\Multiprocess');
$process = $mock->getMock();

$exector->addProcess($process);
$exector->addProcess($process, 'foo');


Tester\Assert::type("array", $exector->getProcesses());
Tester\Assert::equal(2, count($exector->getProcesses()));

$i = 0;
foreach ($exector->getProcesses() as $execute) {
	Tester\Assert::type('ProcessManager\Execute', $execute);

	$i++;
	if ($i === 1) {
		Tester\Assert::null($execute->getNamespace());
	}
	if ($i === 2) {
		Tester\Assert::equal("foo", $execute->getNamespace());
	}

	Tester\Assert::equal($process, $execute->getProcess());
}

Tester\Assert::equal($reader, $exector->getReader());


class FooException extends \ProcessManager\ProcessException {

	protected $message = "Something";

}

Tester\Assert::false($exector->getCatchException());
Tester\Assert::false($exector->handleException(new FooException()));

$exector->onException[] = function(FooException $e) {
	Tester\Assert::equal("Something", $e->getMessage());
};

$exector->setCatchException(TRUE);

Tester\Assert::true($exector->handleException(new FooException()));

