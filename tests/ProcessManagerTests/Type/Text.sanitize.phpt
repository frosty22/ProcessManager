<?php

require __DIR__ . "/../../bootstrap.php";

$text = new \ProcessManager\Type\Text();

Tester\Assert::equal("foo", $text->sanitize("foo"));
Tester\Assert::equal("123", $text->sanitize(123));
Tester\Assert::equal("15.5", $text->sanitize(15.5));

Tester\Assert::exception(function(){
	$integer = new \ProcessManager\Type\Integer();
	$integer->sanitize(array());
}, 'ProcessManager\InvalidArgumentException');

