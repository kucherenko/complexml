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
 * @see CompleXml/Validate/Abstract.php
 */
#require_once 'CompleXml/Validate/Abstract.php';
/**
 * Проверяет строку
 */
class CompleXml_Validate_String extends CompleXml_Validate_Abstract
{
    /**
     * Minimal symbols in string
     *
     * @var int
     */
    private $_min_length = null;
    /**
     * Maximal symbols in string
     *
     * @var int
     */
    private $_max_length = null;
    /**
     * @return int
     */
    public function getMaxLength ()
    {
        return $this->_max_length;
    }
    /**
     * @return int
     */
    public function getMinLength ()
    {
        return $this->_min_length;
    }
    /**
     * @param int $max_length
     */
    public function setMaxLength ($max_length)
    {
        if (is_null($max_length)){
            $this->_max_length = $max_length;
            return ;
        }
        if ($max_length<0){
            throw new CompleXml_Validate_Exception('The maximum must be less than or equal of zero');
        }
        if (! is_null($this->_min_length) && $max_length < $this->_min_length) {
            throw new CompleXml_Validate_Exception('The maximum must be greater than or equal to the minimum length');
        }
        $this->_max_length = $max_length;
    }
    /**
     * @param int $min_length
     */
    public function setMinLength ($min_length)
    {
        if (is_null($min_length)){
            $this->_min_length = $min_length;
            return ;
        }
        if ($min_length<0){
            throw new CompleXml_Validate_Exception('The minimum must be less than or equal of zero');
        }
        if (! is_null($this->_max_length) && $min_length > $this->_max_length) {
            throw new CompleXml_Validate_Exception('The minimum must be less than or equal to the maximum length');
        }
        $this->_min_length = $min_length;
    }
    public function isValid ($value)
    {
        if (is_null($value)|| is_bool($value)) {
            $this->setError('Value is null');
            return false;
        }

        if (!is_null($this->_min_length)||!is_null($this->_max_length))
        {
            $valueString = (string) $value;

            $length = mb_strlen($valueString, 'UTF8');
            if (null !== $this->_min_length && $length < $this->_min_length) {
                $this->setError('String to short');
                return false;
            }
            if (null !== $this->_max_length && $this->_max_length < $length) {
                $this->setError('String to long');
                return false;
            }
        }
        return true;
    }
}