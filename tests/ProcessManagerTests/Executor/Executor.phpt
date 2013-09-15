<?php

require __DIR__ . "/../../bootstrap.php";


$mock = new \Mockista\MockBuilder('ProcessManager\Reader\FormReader');
$reader = $mock->getMock();


$exector = new \ProcessManager\Executor($reader);

