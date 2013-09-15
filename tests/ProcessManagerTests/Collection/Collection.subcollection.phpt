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

Tester\Assert::equal("bar", $collection->foo);
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

