<?php

require __DIR__ . "/../../bootstrap.php";

$text = new \ProcessManager\Type\Text();

Tester\Assert::true($text->check("foo"));
Tester\Assert::true($text->check(123));
Tester\Assert::true($text->check(15.5));

Tester\Assert::false($text->check(array(1)));
Tester\Assert::false($text->check(new \stdClass()));


