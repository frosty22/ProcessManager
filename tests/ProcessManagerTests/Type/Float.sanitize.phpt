<?php

require __DIR__ . "/../../bootstrap.php";

$float = new \ProcessManager\Type\Float();

Tester\Assert::equal(123.0, $float->sanitize(123));
Tester\Assert::equal(123.123, $float->sanitize("123.123"));
Tester\Assert::equal(123.0, $float->sanitize("123"));
Tester\Assert::equal(13.5, $float->sanitize(13.5));

Tester\Assert::exception(function(){
	$float = new \ProcessManager\Type\Float();
	$float->sanitize("foo");
}, 'ProcessManager\InvalidArgumentException');
