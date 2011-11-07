<?php

/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Auth
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */
/**
 * Authentication class
 */
class CompleXml_Auth
{
    
    /**
     * @var CompleXml_Auth
     */
    static private $_instance = null;

    /**
     * @var CompleXml_Auth_Result
     */
    private $_result;

    /**
     * @var CompleXml_Dataset_Session
     */
    private $_session;
    
    protected function  __construct()
    {
        $this->_result = new CompleXml_Auth_Result(CompleXml_Auth_Result::FAILED, null);

        $this->_session = new CompleXml_Dataset_Session();

        $result = $this->_session->getValue(__CLASS__);

        if (!is_null($result)) {
            $this->_result = $result;
        }
    }

    /**
     * @return CompleXml_Auth
     */
    static public function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new CompleXml_Auth();
        }
        return self::$_instance;
    }
    
    public function isAuth()
    {
        return $this->getResult()->isValid();
    }
    
    public function getUser()
    {
        return $this->getResult()->getUser();
    }

    /**
     * return authenticate result
     * @return CompleXml_Auth_Result
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * @param CompleXml_Auth_Result $result
     */
    public function setResult(CompleXml_Auth_Result $result)
    {
        $this->_result = $result;
        $this->_commitResult();
    }

    /**
     * @param mixed $user
     */
    public function authenticate($user)
    {
        $this->getResult()->setDate(time());
        $this->getResult()->setStatus(CompleXml_Auth_Result::OK);
        $this->getResult()->setUser($user);
        $this->_commitResult();
    }

    /**
     * Remove information about user indentity
     */
    public function clearIdentity()
    {
        $this->_result = new CompleXml_Auth_Result(CompleXml_Auth_Result::FAILED, null);
        $this->_commitResult();
    }

    /**
     * Set information to auth object abouth broken authenticate
     */
    public function brokenAuth()
    {
        $this->getResult()->setUser(null);
        $this->getResult()->setDate(time());
        $this->getResult()->setStatus(CompleXml_Auth_Result::FAILED);
        $this->_commitResult();
    }

    /**
     * Return date of last auth operation
     * @param int|string
     */
    public function getDate($format = null)
    {
        return $this->_result->getDate($format);
    }
    
    public function getRoles()
    {
        if ($this->isAuth()) {
            return $this->getResult()->getRoles();
        } else {
            return false;
        }
    }
    
    public function addRole($name)
    {
        $this->getResult()->addRole($name);
        $this->_commitResult();
    }
    
    public function removeRole($name)
    {
        $this->getResult()->removeRole($name);
        $this->_commitResult();
    }
    
    public function setRoles($roles)
    {
        $this->getResult()->setRoles($roles);
        $this->_commitResult();
    }
    
    public function flushRoles()
    {
        $this->getResult()->flushRoles();
        $this->_commitResult();
    }
    
    protected function _commitResult()
    {
        $this->_session->setValue(__CLASS__, $this->getResult());
    }
    
    protected function  __clone()
    {
        
    }
}


