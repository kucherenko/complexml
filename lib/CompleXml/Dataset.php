<?php

/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Dataset
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */
/**
 * Dataset class, main parent of all datasets in framework context
 */
class CompleXml_Dataset implements Countable, Iterator
{
    const RENEW_SOURCE = 1;

    const UPDATE_SOURCE = 2;

    /**
     * Private value for dataset
     *
     * @var array
     */
    private $_source = array();
    /**
     * Index of current element
     *
     * @var int
     */
    private $_index;
    protected static $_validators = array();
    
    public function __construct($source)
    {
        $this->_source = $source;
    }

    /**
     * @return mixed
     */
    public function getValue($name, $default = null, CompleXml_Validate_Abstract $validator = null)
    {
        if (!isset($this->_source[$name])) {
            return $default;
        }

        if (is_null($validator)) {
            return $this->_source[$name];
        } else {
            if ($validator->isValid($this->_source[$name])) {
                return $this->_source[$name];
            } else {
                if ($validator->isValid($default)) {
                    return $default;
                } else {
                    return null;
                }
            }
        }
    }
    
    public function getString($name, $default = null, $min_length = null, $max_length = null)
    {
        if (!in_array('CompleXml_Validate_String', array_keys(self::$_validators))) {
            self::$_validators['CompleXml_Validate_String'] = new CompleXml_Validate_String();
        }
        $validator = self::$_validators['CompleXml_Validate_String'];
        $validator->setMinLength($min_length);
        $validator->setMaxLength($max_length);
        return $this->getValue($name, $default, $validator);
    }
    
    public function getEmail($name, $default = null)
    {
        if (!in_array('CompleXml_Validate_Email', array_keys(self::$_validators))) {
            self::$_validators['CompleXml_Validate_Email'] = new CompleXml_Validate_Email();
        }
        return $this->getValue($name, $default, self::$_validators['CompleXml_Validate_Email']);
    }
    
    public function getDomainName($name, $default = null)
    {
        if (!in_array('CompleXml_Validate_DomainName', array_keys(self::$_validators))) {
            self::$_validators['CompleXml_Validate_DomainName'] = new CompleXml_Validate_DomainName();
        }
        return $this->getValue($name, $default, self::$_validators['CompleXml_Validate_DomainName']);
    }
    
    public function getInt($name, $default = null, $min = null, $max = null)
    {
        $validator = null;

        if (!in_array('CompleXml_Validate_Int', array_keys(self::$_validators))) {
            self::$_validators['CompleXml_Validate_Int'] = new CompleXml_Validate_Int();
        }
        $validator = self::$_validators['CompleXml_Validate_Int'];
        if ($min !== null || $max !== null) {
            $validator->setMin($min);
            $validator->setMax($max);
        }
        $result = $this->getValue($name, $default, $validator);
        if (is_null($result)) {
            return null;
        } else {
            return (int) $result;
        }
    }
    
    public function getBool($name, $default = false)
    {
        if (!in_array('CompleXml_Validate_Boolean', array_keys(self::$_validators))) {
            self::$_validators['CompleXml_Validate_Boolean'] = new CompleXml_Validate_Boolean();
        }
        $validator = self::$_validators['CompleXml_Validate_Boolean'];
        return (boolean) $this->getValue($name, $default, $validator);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setValue($name, $value)
    {
        $this->_source[$name] = $value;
    }
    
    public function setValues($values, $mode = CompleXml_Dataset::RENEW_SOURCE)
    {
        switch ($mode) {
            case CompleXml_Dataset::RENEW_SOURCE:
                $this->_source = (array) $values;
                break;
            case CompleXml_Dataset::UPDATE_SOURCE:
                $values = (array) $values;
                foreach ($values as $key => $value) {
                    $this->_source[$key] = $value;
                }
                break;
        }
    }

    /**
     * Return all values from dataset
     *
     * @return array
     */
    public function getAllValues()
    {
        return (array) $this->_source;
    }
    
    public function __get($name)
    {
        return $this->getValue($name);
    }
    
    public function __set($name, $value)
    {
        $this->setValue($name, $value);
    }
    
    public function __isset($name)
    {
        return isset($this->_source[$name]);
    }
    
    public function __unset($name)
    {
        unset($this->_source[$name]);
    }

    /**
     * Defined by Countable interface
     *
     * @return int
     */
    public function count()
    {
        return count($this->_source);
    }

    /**
     * Defined by Iterator interface
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->_source);
    }

    /**
     * Defined by Iterator interface
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->_source);
    }

    /**
     * Defined by Iterator interface
     *
     */
    public function next()
    {
        next($this->_source);
        $this->_index++;
    }

    /**
     * Defined by Iterator interface
     *
     */
    public function rewind()
    {
        reset($this->_source);
        $this->_index = 0;
    }

    /**
     * Defined by Iterator interface
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->_index < $this->count();
    }
}
