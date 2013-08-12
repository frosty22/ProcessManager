<?php

require __DIR__ . "/../../bootstrap.php";

class MockType extends \ProcessManager\Type\BaseType {


	public function check($value)
	{
		return $value === "foo";
	}

}


$type = new MockType();

Tester\Assert::false($type->isRequired());

$type->setRequired(TRUE);

Tester\Assert::true($type->isRequired());

$type->setRequired(FALSE);

Tester\Assert::false($type->isRequired());

Tester\Assert::equal("foo", $type->sanitize("foo"));


Tester\Assert::exception(function(){
	$type = new MockType();
	$type->sanitize("bar");
}, 'ProcessManager\InvalidArgumentException');
