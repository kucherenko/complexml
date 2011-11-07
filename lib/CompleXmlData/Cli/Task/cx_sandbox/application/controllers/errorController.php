<?php

/**
 * errorController class
 *
 * @generator CompleXml Command Line Tool
 */
class errorController extends CompleXml_Controller{

    public function notfoundAction()
    {
        $exception = $this->Request->getValue('exception');
        $debug_mode = (boolean) CompleXml_Config::getSettings('Application','debug_mode');
        if ($debug_mode){
            $this->View->writeElement('debug_mode', 1);
        }
        if ($exception instanceof Exception){
            $this->View->startElement('exception');
            $this->View->assign('type', get_class($exception));
            $this->View->assign('file', $exception->getFile());
            $this->View->assign('message', $exception->getMessage());
            $this->View->assign('code', $exception->getCode());
            $this->View->assign('line', $exception->getLine());
            $this->View->assign('trace', $exception->getTraceAsString());
            $this->View->endElement();
        }
    }
}

