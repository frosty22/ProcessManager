<?php

require __DIR__ . "/../../bootstrap.php";

$integer = new \ProcessManager\Type\Integer();

Tester\Assert::true($integer->check(123));
Tester\Assert::true($integer->check(-123));
Tester\Assert::true($integer->check("123456"));
Tester\Assert::true($integer->check("-123456"));

Tester\Assert::false($integer->check("foo"));
Tester\Assert::false($integer->check(15.5));
Tester\Assert::false($integer->check(array(1)));
Tester\Assert::false($integer->check(new \stdClass()));


