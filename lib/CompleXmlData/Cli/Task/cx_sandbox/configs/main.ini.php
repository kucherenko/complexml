<?php

define('APPLICATION_HOME', dirname(dirname(__FILE__)));

set_include_path(get_include_path().PATH_SEPARATOR.APPLICATION_HOME.DIRECTORY_SEPARATOR.'lib');

require_once 'CompleXml/Loader.php';
spl_autoload_register(array('CompleXml_Loader', 'autoload'));
