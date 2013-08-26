<?php

require __DIR__ . "/../../bootstrap.php";


class MockType extends \ProcessManager\Type\BaseType {


	public function check($value)
	{
		return true;
	}

}


$mapper = new \ProcessManager\Mapper();
Tester\Assert::type('MockType', $mapper->addType("foo", new MockType()));

Tester\Assert::exception(function(){
	$mapper = new \ProcessManager\Mapper();
	$mapper->addText("foo");
	$mapper->addText("foo");
}, 'ProcessManager\InvalidArgumentException');

Tester\Assert::type('ProcessManager\Type\Text', $mapper->addText("foo2"));
Tester\Assert::type('ProcessManager\Type\Integer', $mapper->addInteger("foo3"));
Tester\Assert::type('ProcessManager\Type\Float', $mapper->addFloat("foo4"));
