<?php

class CompleXml_Auth_Listener extends CompleXml_Listener_Abstract
{
    public function beforeAction($method, $object = null)
    {
        if (is_null($object)) {
            $object = $this->_object;
        }
        $ref = new CompleXml_Reflection_Method(get_class($object), $method);
        $auth = $ref->getAnnotation('auth');
        if (!is_null($auth) && ($auth == 'true')) {
            if (!CompleXml_Auth::getInstance()->isAuth()) {
                throw new CompleXml_Auth_Listener_Exception('Action only for authenticated users');
            }
        }
    }
    
    public function afterAction($method, $object = null)
    {
    
    }
}