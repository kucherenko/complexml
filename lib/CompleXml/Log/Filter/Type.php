<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Log_Filter
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */
#require_once 'CompleXml/Log/Filter/Abstract.php';

/**
 * Фильтрация сообщений по типу
 */
class CompleXml_Log_Filter_Type extends CompleXml_Log_Filter_Abstract 
{
    private $_types = array();
    
    public function __construct($types)
    {
        $this->_types = (array) $types;
    }
    
    public function accept($log)
    {
        if (in_array(@$log['type'], $this->_types)){
            return true;
        }
        return false;
    }
}
