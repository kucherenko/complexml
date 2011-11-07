<?php

class CompleXml_Acl_Listener extends CompleXml_Listener_Abstract
{

    public function beforeAction($method, $object = null)
    {
        if (is_null($object)) {
            $object = $this->_object;
        }
        $ref = new CompleXml_Reflection_Method(get_class($object), $method);
        $allow = (array) $ref->getAnnotation('allow');
        $deny = (array) $ref->getAnnotation('deny');

        $acl = new CompleXml_Acl();

        $resource = $ref->getDeclaringClass()->getName() . $method;
        $acl->addResource(new CompleXml_Acl_Resource($resource));

        foreach ($allow as $role) {
            if ($role == 'all' || $role == '*') {
                $acl->setDefaultAccessStyle('allow');
                continue;
            }
            $acl->addRole(new CompleXml_Acl_Role($role));
            $acl->allow($resource, $role);
        }

        foreach ($deny as $role) {
            if ($role == 'all' || $role == '*') {
                $acl->setDefaultAccessStyle('deny');
                continue;
            }
            $acl->addRole(new CompleXml_Acl_Role($role));
            $acl->deny($resource, $role);
        }

        $roles = (array) CompleXml_Auth::getInstance()->getRoles();

        $is_deny_count = 0;
        
        foreach ($roles as $role) {
            if ($acl->isDeny($resource, $role)) {
                $is_deny_count++;
            }
        }
        if ($is_deny_count == count($roles)) {
            throw new CompleXml_Acl_Listener_Exception('Resource not allowed');
        }
    }
    
    public function afterAction($method, $object = null)
    {
        
    }
}