<?php
if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'configs/cli.ini.php')) {
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'configs/cli.ini.php';
} elseif (file_exists(getcwd() . DIRECTORY_SEPARATOR . 'configs/cli.ini.php')) {
    require_once getcwd() . DIRECTORY_SEPARATOR . 'configs/cli.ini.php';
}

if (!defined('APPLICATION_HOME')) {
    define('APPLICATION_HOME', getcwd());
}
set_include_path(get_include_path() . PATH_SEPARATOR . APPLICATION_HOME . DIRECTORY_SEPARATOR . 'lib');
require_once 'CompleXml/Loader.php';
spl_autoload_register(array('CompleXml_Loader', 'autoload'));


$writer = new CompleXml_Log_Writer_Stream(STDOUT);
$writer->setFormat(new CompleXml_Log_Format_Console());

CompleXml_Log::addWriter($writer);

CompleXml_Log::notice('CompleXml Framework command line tool');
CompleXml_Log::notice('Current APPLICATION_HOME="' . APPLICATION_HOME . '"');

$tasks = (array) CompleXml_Config::getSettings('Cli', 'tasks');
foreach ($tasks as $task) {
    if (!class_exists($task)) {
        CompleXml_Loader::autoload($task);
    }
}

if (($_SERVER['argc'] > 1) || !extension_loaded('readline')) {
    try {
        CompleXml_Log::info('Usage:');
        CompleXml_Log::info("\tcx [--task] task-name [--action] action-name [tasks parameters ...]");
        CompleXml_Cli::run($_SERVER['argv']);
    } catch (CompleXml_Cli_Exception $e) {
        CompleXml_Log::info("\tuse cx help [task-name] for more informations");
        CompleXml_Log::err($e->getMessage());
    } catch (Exception $e) {
        CompleXml_Log::emerg($e->getMessage());
        CompleXml_Log::debug(var_export($e, true));
    }
    exit(1);
}

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    exit(1);
}

readline_completion_function(array('CompleXml_Cli_Task_Help', 'completion'));
CompleXml_Log::info('Usage:');
CompleXml_Log::info("\ttask-name [--action] action-name [tasks parameters ...]");
CompleXml_Loader::autoload('CompleXml_Cli_Task_Exit');
while (true) {
    $s = readline("\033[1;37m[CX]>\033[0m ");
    readline_add_history($s);
    $s = trim($s);
    if (empty($s)) {
        continue;
    }
    $argv = explode(' ', $s);
    $argv = array_merge(array('[CX]> '), $argv);
    $_argv = array();
    foreach ($argv as $row) {
        $line = trim($row);
        if (!empty($line)) {
            $_argv[] = $line;
        }
    }
    $argv = $_argv;
    if ($argv[1] == 'help') {
        CompleXml_Log::info('Usage:');
        CompleXml_Log::info("\ttask-name [--action] action-name [tasks parameters ...]");
    }
    try {
        CompleXml_Cli::run($argv);
    } catch (CompleXml_Cli_Exception $e) {
        CompleXml_Log::info("\tuse help [task-name] for more informations");
        CompleXml_Log::err($e->getMessage());
    } catch (Exception $e) {
        CompleXml_Log::emerg($e->getMessage());
        CompleXml_Log::debug(var_export($e, true));
    }
    print "\n";
}
