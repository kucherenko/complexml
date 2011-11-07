<?php

/**
 * Class for manage access lists.
 *
 * @throws CompleXml_Acl_Exception
 *
 * @author Andrey Kucherenko <andrey@kucherenko.org>
 */
class CompleXml_Acl
{
    private $_roles = array();

    private $_resources = array();

    private $_allow = array();

    private $_deny = array();

    private $_default_access_style = 'deny';

    private $_permits_access_styles = array('allow', 'deny');

    public function __construct()
    {
        $settings = CompleXml_Config::readComponentSettings(__CLASS__);
        if (isset($settings['default'])) {
            $this->setDefaultAccessStyle($settings['default']);
        }
    }

    /**
     * Set default style for access list behaviour.
     *
     * @throws CompleXml_Acl_Exception
     * @param  $name
     * @return CompleXml_Acl
     */
    public function setDefaultAccessStyle($name)
    {
        if (in_array($name, $this->_permits_access_styles)) {
            $this->_default_access_style = $name;
        } else {
            throw new CompleXml_Acl_Exception('Default access style named - "' . $name . '" not permited');
        }
        return $this;
    }
    /**
     * Check is allow for all by default.
     * 
     * @return boolean
     */
    public function isDefaultAllow()
    {
        return $this->_default_access_style == 'allow';
    }

    /**
     * Check is allow for all by default.
     * 
     * @return boolean
     */
    public function isDefaultDeny()
    {
        return $this->_default_access_style == 'deny';
    }

    /**
     * Attach resource to access list.
     *
     * @param CompleXml_Acl_Role_Interface $role
     * @return CompleXml_Acl
     */
    public function addRole(CompleXml_Acl_Role_Interface $role)
    {
        $this->_roles[$role->getId()] = $role;
        return $this;
    }
    /**
     * Attach resource to access list.
     *
     * @param CompleXml_Acl_Resource_Interface $resource
     * @return CompleXml_Acl
     */
    public function addResource(CompleXml_Acl_Resource_Interface $resource)
    {
        $this->_resources[$resource->getId()] = $resource;
        return $this;
    }

    /**
     * Return role from access list by name.
     *
     * @param  string $name
     *
     * @return CompleXml_Acl_Role_Interface|boolean
     */
    public function getRole($name)
    {
        if (empty($this->_roles[$name])) {
            return false;
        }
        return $this->_roles[$name];
    }

    /**
     * Return resource by name.
     *
     * @param  $name name of resource
     * @return CompleXml_Acl_Resource_Interface|bool
     */
    public function getResource($name)
    {
        if (empty($this->_resources[$name])) {
            return false;
        }
        return $this->_resources[$name];
    }
    /**
     * Remove role from access list.
     *
     * @throws CompleXml_Acl_Exception
     * @param  $name name of role
     * @return CompleXml_Acl
     */
    public function removeRole($name)
    {
        if (empty($this->_roles[$name])) {
            throw new CompleXml_Acl_Exception('Role not found');
        }
        unset($this->_roles[$name]);
        return $this;
    }

    /**
     * Remove resource from access list
     *
     * @throws CompleXml_Acl_Exception
     * @param  $name
     * @return CompleXml_Acl
     */
    public function removeResource($name)
    {
        if (empty($this->_resources[$name])) {
            throw new CompleXml_Acl_Exception('Resourse not found');
        }
        unset($this->_resources[$name]);
        return $this;
    }

    /**
     * Make allow rule for access to $resource_name for $role_name.
     *
     * @throws CompleXml_Acl_Exception
     * @param  $resource_name
     * @param  $role_name
     * @return CompleXml_Acl
     */
    public function allow($resource_name, $role_name)
    {
        if ($this->hasResource($resource_name) && $this->hasRole($role_name)) {
            if (!isset($this->_allow[$resource_name])) {
                $this->_allow[$resource_name] = null;
            }
            if (!is_array($this->_allow[$resource_name]) || !in_array($role_name, $this->_allow[$resource_name])) {
                $this->_allow[$resource_name][] = $role_name;
            }
            if (!isset($this->_deny[$resource_name])) {
                $this->_deny[$resource_name] = null;
            }
            if (is_array($this->_deny[$resource_name]) && in_array($role_name, $this->_deny[$resource_name])) {
                $key = array_search($role_name, $this->_deny[$resource_name]);
                unset ($this->_deny[$resource_name][$key]);
            }
        } elseif (!$this->hasResource($resource_name)) {
            throw new CompleXml_Acl_Exception('Resource not found');
        } elseif (!$this->hasRole($role_name)) {
            throw new CompleXml_Acl_Exception('Role not found');
        }
        return $this;
    }

    /**
     * Make deny rule for access to $resource_name for $role_name.
     *
     * @throws CompleXml_Acl_Exception
     * @param  $resource_name
     * @param  $role_name
     * @return CompleXml_Acl
     */
    public function deny($resource_name, $role_name)
    {
        if ($this->hasResource($resource_name) && $this->hasRole($role_name)) {
            if (!isset($this->_deny[$resource_name])) {
                $this->_deny[$resource_name] = null;
            }
            if (!is_array($this->_deny[$resource_name]) || !in_array($role_name, $this->_deny[$resource_name])) {
                $this->_deny[$resource_name][] = $role_name;
            }
            if (!isset($this->_allow[$resource_name])) {
                $this->_allow[$resource_name] = null;
            }
            if (is_array($this->_allow[$resource_name]) && in_array($role_name, $this->_allow[$resource_name])) {
                $key = array_search($role_name, $this->_allow[$resource_name]);
                unset ($this->_allow[$resource_name][$key]);
            }
        } elseif (!$this->hasResource($resource_name)) {
            throw new CompleXml_Acl_Exception('Resource not found');
        } elseif (!$this->hasRole($role_name)) {
            throw new CompleXml_Acl_Exception('Role not found');
        }
        return $this;
    }

    /**
     * Check is access list have role with $name.
     * @param  $name
     * @return boolean
     */
    public function hasRole($name)
    {
        return (boolean) !empty($this->_roles[$name]);
    }

    /**
     * Check is access list have resource with $name.
     *
     * @param  $name name of resource
     *
     * @return boolean
     */
    public function hasResource($name)
    {
        return (boolean) !empty($this->_resources[$name]);
    }

    /**
     * Check is $resource_name allowed for $role_name.
     *
     * @param  $resource_name
     * @param  $role_name
     *
     * @return boolean
     */
    public function isAllow($resource_name, $role_name)
    {
        if ($this->isDefaultAllow()) {
            if (isset($this->_deny[$resource_name])){
                return (boolean) !in_array($role_name, (array) $this->_deny[$resource_name]);
            } else {
                return true;
            }
        }
        if (isset($this->_allow[$resource_name])) {
            return (boolean) in_array($role_name, (array) $this->_allow[$resource_name]);
        }
        return false;
    }

    /**
     * Check is $resource_name denied for $role_name.
     *
     * @param  $resource_name
     * @param  $role_name
     *
     * @return boolean
     */
    public function isDeny($resource_name, $role_name)
    {
        if ($this->isDefaultDeny()) {
            if (isset($this->_allow[$resource_name])) {
                return (boolean) !in_array($role_name, (array) $this->_allow[$resource_name]);
            } else {
                return true;
            }
        }
        if (isset($this->_deny[$resource_name])){
            return (boolean) in_array($role_name, (array) $this->_deny[$resource_name]);
        }
        return false;
    }
}
