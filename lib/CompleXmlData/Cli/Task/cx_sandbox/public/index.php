<?php
require_once '../configs/main.ini.php';

/**
 * @domain {enter your public domain}
 */
class PublicRouter extends CompleXml_Router
{
}

/**
 * @domain *
 */
 class DevRouter extends PublicRouter
{
    public function init()
    {
        CompleXml_Config::setSettings('Output_Xml',
            array('only_xml'=>$this->Request->getBool('xml'))
        );
        CompleXml_Config::setSettings('Application', array('debug_mode'=>true));
    }
}

CompleXml_Application::run(); 