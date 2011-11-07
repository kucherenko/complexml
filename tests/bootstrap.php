<?php

set_include_path(dirname(dirname(__FILE__)).'/lib' . PATH_SEPARATOR . get_include_path());

require_once 'CompleXml/Loader.php';

spl_autoload_register(array('CompleXml_Loader', 'autoload'));
