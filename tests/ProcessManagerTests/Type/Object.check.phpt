<?php

require __DIR__ . "/../../bootstrap.php";



$object = new \ProcessManager\Type\Object("stdClass");

Tester\Assert::true($object->check(new stdClass()));

Tester\Assert::false($object->check(123));
Tester\Assert::false($object->check("stdClass"));


$object = new \ProcessManager\Type\Object(new stdClass());

Tester\Assert::true($object->check(new stdClass()));