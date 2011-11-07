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
 * Проверяет, является ли значение числом
 */
class CompleXml_Validate_Int extends CompleXml_Validate_Abstract
{
    /**
     * Minimal value
     *
     * @var int
     */
    private $_min = null;
    /**
     * Maximal value
     *
     * @var int
     */
    private $_max = null;
    /**
     * @return int
     */
    public function getMax ()
    {
        return $this->_max;
    }
    /**
     * @return int
     */
    public function getMin ()
    {
        return $this->_min;
    }
    /**
     * @param int $max
     */
    public function setMax ($max)
    {
        if (! is_null($this->_min) && $max < $this->_min) {
            #require_once 'CompleXml/Validate/Exception.php';
            throw new CompleXml_Validate_Exception('The maximum must be greater than or equal to the minimum');
        }
        $this->_max = $max;
    }
    /**
     * @param int $min
     */
    public function setMin ($min)
    {
        if (! is_null($this->_max) && $min > $this->_max) {
            throw new CompleXml_Validate_Exception('The minimum must be less than or equal to the maximum');
        }
        $this->_min = $min;
    }
    public function isValid ($value)
    {
        $options = array();
        if (!is_null($this->getMin())){
            $options['options']['min_range'] = $this->getMin();
        }
        if (!is_null($this->getMax())){
            $options['options']['max_range'] = $this->getMax();
        }
        return filter_var($value, FILTER_VALIDATE_INT, $options)!==false;
    }

}