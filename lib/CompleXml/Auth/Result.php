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
 * Class result of user authenticate
 */
class CompleXml_Auth_Result
{
    const OK = 1;

    const NOT_AUTH = 0;

    const FAILED = - 1;

    /**
     * Authentification date
     * @var int
     */
    private $_auth_date;
    /**
     * Authentificated user
     * @var mixed
     */
    private $_user;
    /**
     * Status of authentification
     * @var int
     */
    private $_code = 0;
    private $_roles = array();

    /**
     * Make Auth_Result object
     * @param int $status
     * @param mixed $user
     */
    public function  __construct($status, $user)
    {
        $this->_auth_date = time();
        $this->_code = (int) $status;
        $this->_user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->_user = $user;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_code;
    }

    /**
     * Check authentification status
     * @return bool
     */
    public function isValid()
    {
        if ($this->_code > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set auth status
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->_code = $status;
    }

    /**
     * Return date of authentification
     * @param string $format
     * @return int|string
     */
    public function getDate($format = null)
    {
        if (is_null($format)) {
            return $this->_auth_date;
        }
        return date($format, $this->_auth_date);
    }

    /**
     * Set authentification date
     * @param int $timestamp
     */
    public function setDate($timestamp)
    {
        $this->_auth_date = $timestamp;
    }

    /**
     * Add role to user roles
     * @param string $name
     */
    public function addRole($name)
    {
        if (!isset($this->_roles[$name])) {
            $this->_roles[$name] = $name;
        }
    }
    /**
     * Set array of roles
     * @param array $roles
     */
    public function setRoles($roles)
    {
        foreach ($roles as $role){
            $this->addRole($role);
        }
    }
    /**
     * @return array
     */
    public function getRoles()
    {
        if (empty($this->_roles)) {
            return array();
        } else {
            return array_keys($this->_roles);
        }

    }
    /**
     * Remove role from result
     * @param string $name
     */
    public function removeRole($name)
    {
        unset($this->_roles[$name]);
    }
    /**
     * Clean all roles of user
     */
    public function flushRoles()
    {
        unset($this->_roles);
    }
}




