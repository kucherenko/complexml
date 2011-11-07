<?php

$CONFIG = array();

$CONFIG['templates'] = CompleXml_Config::getSettings('Application', 'document_root').DIRECTORY_SEPARATOR.'templates';

$CONFIG['locales'] = CompleXml_Config::getSettings('Application', 'document_root').DIRECTORY_SEPARATOR.'locales';

return $CONFIG;
