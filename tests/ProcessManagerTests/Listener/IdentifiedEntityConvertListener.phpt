<?php

namespace Ale\Entities {
	class IdentifiedEntity { }
}

namespace {

require __DIR__ . "/../../bootstrap.php";

class FooEntity extends \Ale\Entities\IdentifiedEntity
{ }


class FooProcess implements \ProcessManager\Process\IProcess {

	public function getRequiredMapper() {
		$mapper = new \ProcessManager\Mapper();
		$mapper->addObject('another', 'DateTime');
		$mapper->addObject('entity', 'FooEntity');
		$mapper->addObject('next', 'FooEntity');
		return $mapper;
	}

	public function execute(\ProcessManager\Collection $collection) {
	}

}


$mock = new \Mockista\MockBuilder('Kdyby\Doctrine\EntityManager');
$mock->find('FooEntity', 123)->once()->andReturn(new FooEntity());
$mock->find('FooEntity', 234)->once()->andReturn(NULL);
$em = $mock->getMock();


$listener = new \ProcessManager\Listener\IdentifiedEntityConvertListener($em);
Tester\Assert::equal(array('ProcessManager\ProcessManager::onBeforeProcessCheck'), $listener->getSubscribedEvents());


$process = new FooProcess();


$collection = new \ProcessManager\Collection(array(
	'entity' => 123,
	'next' => new FooEntity()
));

$listener->onBeforeProcessCheck($process, $collection);

Tester\Assert::type('FooEntity', $collection->entity);
Tester\Assert::type('FooEntity', $collection->next);
Tester\Assert::null($collection->another);

Tester\Assert::exception(function()use($listener, $process){

	$collection = new \ProcessManager\Collection(array(
		'entity' => 234
	));

	$listener->onBeforeProcessCheck($process, $collection);
}, 'ProcessManager\InvalidIdentifiedException');

}