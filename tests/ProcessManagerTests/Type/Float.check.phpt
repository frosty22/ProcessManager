<?php

require __DIR__ . "/../../bootstrap.php";

$float = new \ProcessManager\Type\Float();

Tester\Assert::true($float->check(123));
Tester\Assert::true($float->check(-123));
Tester\Assert::true($float->check("123456"));
Tester\Assert::true($float->check("-123456"));
Tester\Assert::true($float->check(15.5));
Tester\Assert::true($float->check("15.5"));

Tester\Assert::false($float->check("foo"));
Tester\Assert::false($float->check(array(1)));
Tester\Assert::false($float->check(new \stdClass()));


