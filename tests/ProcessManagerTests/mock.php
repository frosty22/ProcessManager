<?php

class MockManager extends \ProcessManager\ProcessManager {
	public function __construct() { }
	public function execute(\ProcessManager\Executor $executor) { }
}


class MockExecutor extends \ProcessManager\Executor {
	public function __construct() { }
	public function run(\ProcessManager\ProcessManager $manager) { }
}

$manager = new MockManager();
$executor = new MockExecutor();