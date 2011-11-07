<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Config
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */
 /**
  * Абстрактный класс, предназначен для реализации классов для
  * вычитывания конфигураций
  */
abstract class CompleXml_Config_Reader_Abstract
{
    public function read_file($filename)
    {
        if (!file_exists($filename)){
            throw new CompleXml_Config_Exception('File not exists');
        }
        if (!is_readable($filename)){
            throw new CompleXml_Config_Exception('File not readable');
        }
        $this->_read($filename);
    }
    
    abstract protected function _read($filename);
}