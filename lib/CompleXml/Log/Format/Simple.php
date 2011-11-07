<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Log_Format
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * Простое форматирование текста сообщений журнала
 */
class CompleXml_Log_Format_Simple extends CompleXml_Log_Format_Abstract
{
    private $_format;
    public function __construct ($format = null)
    {
        if (is_null($format)) {
            $this->_format = '%date%  %typename%(%type%) %message% ' . PHP_EOL;
        }
    }
    public function build ($log)
    {
        $output = $this->_format;
        foreach ($log as $name => $value) {
            $output = str_replace('%'.$name.'%', $value, $output);
        }
        return $output;
    }
}
