<?php

require __DIR__ . "/../../bootstrap.php";


class TestUser
{

}


class TestProcess implements \ProcessManager\Process\IProcess
{

	/**
	 * Return required mapper, for data input.
	 * @return \ProcessManager\Mapper
	 */
	public function getRequiredMapper() {
		$mapper = new \ProcessManager\Mapper();

		$mapper->addText("name")
			->setRequired();

		$mapper->addInteger("age")
			->setRequired();

		$mapper->addObject("wife", "TestUser");

		$mapper->addObject("mother", "TestUser")
			->setRequired();

		$mapper->addCollection("col");

		$mapper->addCollection("colRequired")
			->setRequired();

		return $mapper;
	}


	/**
	 * @param \ProcessManager\Collection $collection
	 * @return mixed
	 */
	public function execute(\ProcessManager\Collection $collection) {
		Tester\Assert::equal("Jan Novak", $collection->name);
		Tester\Assert::equal(20, $collection->age);
		Tester\Assert::equal(new TestUser(), $collection->mother);
		Tester\Assert::equal(new \ProcessManager\Collection(array("key" => "subvalue")), $collection->colRequired);

		if (isset($collection->wife)) {
			Tester\Assert::equal(new TestUser(), $collection->wife);
		} else {
			Tester\Assert::null($collection->wife);
		}

		if (isset($collection->col)) {
			Tester\Assert::equal(new \ProcessManager\Collection(), $collection->col);
		} else {
			Tester\Assert::null($collection->col);
		}
	}

}



$mock = new \Mockista\MockBuilder('Nette\Forms\Form');
$mock->getValues(TRUE)
	->once()
	->andReturn(array(
		"name" => "Jan Novak",
		"age" => 20,
		"mother" => new TestUser(),
		"subkey" => "subvalue"
	));

$form = $mock->getMock();
$reader = new \ProcessManager\Reader\FormReader($form);
$reader->rename("subkey", "colRequired.key");

Tester\Assert::equal("subvalue", $reader->getCollection()->colRequired->key);


$manager = new \ProcessManager\ProcessManager();

$executor = new \ProcessManager\Executor($reader);
$executor->addProcess(new TestProcess());

$manager->execute($executor);



$mock = new \Mockista\MockBuilder('Nette\Forms\Form');
$mock->getValues(TRUE)
	->once()
	->andReturn(array(
		"name" => "Jan Novak",
		"age" => 20,
		"mother" => new TestUser(),
		"wife" => new TestUser(),
		"colRequired.key" => "subvalue"
	));

$form = $mock->getMock();
$reader = new \ProcessManager\Reader\FormReader($form);

$executor = new \ProcessManager\Executor($reader);
$executor->addProcess(new TestProcess());

$manager->execute($executor);



