<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Log_Writer
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

 /**
  *  Абстрактный класс для записи системных журналов
  */
abstract class CompleXml_Log_Writer_Abstract
{
    protected $_filters = array();
    
    protected $_format = NULL;
    
    public function setFormat(CompleXml_Log_Format_Abstract $format)
    {
        $this->_format = $format;
    }
    
    public function addFilter(CompleXml_Log_Filter_Abstract $filter)
    {
        $this->_filters[] = $filter;
    }
    
    public function write($log)
    {
        foreach ($this->_filters as $filter) {
        	if (!$filter->accept($log)){
        	    return;
        	}
        }
        $this->_write($log);
    }

    abstract public function __destruct();
    
    abstract protected function _write($log);
    
}