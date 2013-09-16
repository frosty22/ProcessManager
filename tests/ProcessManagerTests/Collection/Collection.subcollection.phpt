<?php

require __DIR__ . "/../../bootstrap.php";


$collection = new \ProcessManager\Collection(array(
	"foo" => "bar",
	"collection.bar" => "baz",
	"collection.foo" => "bar",
	"sub.sub.key1" => "foo",
	"sub.sub.key2" => "bar",
	"sub.key1" => "baz"
));

Tester\Assert::true(isset($collection->foo));
Tester\Assert::equal("bar", $collection->foo);
unset($collection->foo);
Tester\Assert::null($collection->foo);
Tester\Assert::false(isset($collection->foo));
$collection->foo = "bar";

Tester\Assert::equal(new \ProcessManager\Collection(array("bar" => "baz", "foo" => "bar")), $collection->collection);
Tester\Assert::equal("baz", $collection->collection->bar);
Tester\Assert::equal("bar", $collection->collection->foo);

Tester\Assert::equal("foo", $collection->sub->sub->key1);
Tester\Assert::equal("bar", $collection->sub->sub->key2);
Tester\Assert::equal("baz", $collection->sub->key1);

$target = "namespace123.namespace234.target";
$collection->$target = 123;

Tester\Assert::equal(123, $collection->namespace123->namespace234->target);
Tester\Assert::type('ProcessManager\Collection', $collection->namespace123);
Tester\Assert::type('ProcessManager\Collection', $collection->namespace123->namespace234);

Tester\Assert::type('ProcessManager\Collection', $collection->{"sub.sub"});
Tester\Assert::equal(123, $collection->{"namespace123.namespace234.target"});
Tester\Assert::equal(123, $collection["namespace123.namespace234.target"]);
Tester\Assert::equal(123, $collection->namespace123["namespace234.target"]);
Tester\Assert::equal(123, $collection->namespace123->namespace234["target"]);
Tester\Assert::null($collection["not.exist.foo"]);


Tester\Assert::true(isset($collection["collection.bar"]));
unset($collection["collection.bar"]);
Tester\Assert::null($collection->collection->bar);
Tester\Assert::false(isset($collection["collection.bar"]));

Tester\Assert::true(isset($collection["sub.sub"]));
unset($collection["sub.sub"]);
Tester\Assert::null($collection->sub->sub);
Tester\Assert::false(isset($collection["sub.sub"]));




