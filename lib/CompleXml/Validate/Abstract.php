<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Validate
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

 /**
  * Абстрактный класс, общий родитель для всех проверяющих классов
  */
abstract class CompleXml_Validate_Abstract
{
    /**
     * Errors array
     *
     * @var array
     */
    private $_errors = array();
    /**
     * @return array
     */
    public function getErrors ()
    {
        return $this->_errors;
    }
    /**
     * @param string $error
     */
    public function setError ($error)
    {
        $this->_errors[] = $error;
    }

    public function __toString()
    {
        $class = new ReflectionClass($this);
        return str_replace('CompleXml_Validate_', '', $class->name);
    }

    /**
     * Main method for validate value
     *
     * @param boolean $value
     */
    abstract public function isValid ($value);
}