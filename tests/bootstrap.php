<?php


if (@!include __DIR__ . '/../../../../vendor/autoload.php') {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}

$dumper = new Tester\Dumper();

// configure environment
Tester\Environment::setup();
class_alias('Tester\Assert', 'Assert');
date_default_timezone_set('Europe/Prague');


// create temporary directory
define('TEMP_DIR', __DIR__ . '/tmp/' . getmypid());
@mkdir(dirname(TEMP_DIR)); // @ - directory may already exist
Tester\Helpers::purge(TEMP_DIR);


$_SERVER = array_intersect_key($_SERVER, array_flip(array('PHP_SELF', 'SCRIPT_NAME', 'SERVER_ADDR', 'SERVER_SOFTWARE', 'HTTP_HOST', 'DOCUMENT_ROOT', 'OS', 'argc', 'argv')));
$_SERVER['REQUEST_TIME'] = 1234567890;
$_ENV = $_GET = $_POST = array();


require __DIR__ . "/../src/ProcessManager/Reader/IReader.php";
require __DIR__ . "/../src/ProcessManager/Reader/XmlReader.php";
require __DIR__ . "/../src/ProcessManager/Reader/FormReader.php";
require __DIR__ . "/../src/ProcessManager/Reader/CsvReader.php";
require __DIR__ . "/../src/ProcessManager/Reader/ArrayReader.php";

require __DIR__ . "/../src/ProcessManager/Process/IProcess.php";
require __DIR__ . "/../src/ProcessManager/Process/MultiProcess.php";

require __DIR__ . "/../src/ProcessManager/Converter/IConverter.php";

require __DIR__ . "/../src/ProcessManager/Type/IType.php";
require __DIR__ . "/../src/ProcessManager/Type/BaseType.php";
require __DIR__ . "/../src/ProcessManager/Type/Text.php";
require __DIR__ . "/../src/ProcessManager/Type/Integer.php";
require __DIR__ . "/../src/ProcessManager/Type/Float.php";
require __DIR__ . "/../src/ProcessManager/Type/Object.php";

require __DIR__ . "/../src/ProcessManager/Execute/IExecute.php";
require __DIR__ . "/../src/ProcessManager/Execute/BaseExecute.php";
require __DIR__ . "/../src/ProcessManager/Execute/Converter.php";
require __DIR__ . "/../src/ProcessManager/Execute/Process.php";
require __DIR__ . "/../src/ProcessManager/Executor.php";
require __DIR__ . "/../src/ProcessManager/exceptions.php";
require __DIR__ . "/../src/ProcessManager/Collection.php";
require __DIR__ . "/../src/ProcessManager/Mapper.php";
require __DIR__ . "/../src/ProcessManager/ProcessManager.php";
require __DIR__ . "/../src/ProcessManager/Listener/IdentifiedEntityConvertListener.php";



