<?php
if (!defined('APPLICATION_HOME')) {
    define('APPLICATION_HOME', dirname(dirname(__FILE__)));
}
return  array(
    'controllers'=> APPLICATION_HOME.
        DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'controllers',
    'models' => APPLICATION_HOME.
        DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'models',
    'configs' => APPLICATION_HOME.
        DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'configs',
    'logs'=> APPLICATION_HOME.
        DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'logs',
    'document_root'=>APPLICATION_HOME.
        DIRECTORY_SEPARATOR.'public',    
    'cache'=> APPLICATION_HOME.
        DIRECTORY_SEPARATOR.'cache',
    'cache_driver'=> new CompleXml_Cache_Driver_File(array('dirname'=>APPLICATION_HOME.
        DIRECTORY_SEPARATOR.'cache')),

    'default_locale'=>'en_EN',

    'debug_mode'=>false,

    'router_cache'=> true,

    'controller_template'=> '%sController',

    'action_template'=> '%sAction',

    'default_router'=> 'CompleXml_Router_Default',

    'default_action'=> 'index',

    'default_controller'=> 'index',

    'default_controller'=> 'index',

    'listeners' => array('CompleXml_Listener_Behaviour'),

    'outputs' => array('CompleXml_Output_Xml', 'CompleXml_Output_Browser', 'CompleXml_Output_Xslt')
);


