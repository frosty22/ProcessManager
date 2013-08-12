<?php

require __DIR__ . "/../../bootstrap.php";

$integer = new \ProcessManager\Type\Integer();

Tester\Assert::equal(123, $integer->sanitize(123));
Tester\Assert::equal(123, $integer->sanitize("123"));


Tester\Assert::exception(function(){
	$integer = new \ProcessManager\Type\Integer();
	$integer->sanitize("foo");
}, 'ProcessManager\InvalidArgumentException');