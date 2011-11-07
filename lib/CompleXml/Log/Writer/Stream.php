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
 * Класс реализующий запись журналов в любой поток
 */
final class CompleXml_Log_Writer_Stream extends CompleXml_Log_Writer_Abstract
{
    private $_stream;
    public function __construct ($resource, $mode = 'a')
    {
        if (is_resource($resource)) {
            if (get_resource_type($resource) != 'stream') {
                throw new CompleXml_Log_Exception('Can\'t use this resource, because resource not a stream');
            }
            $this->_stream = $resource;
        } else {
            if (! $this->_stream = @fopen($resource, $mode)) {
                throw new CompleXml_Log_Exception('Can\'n open resource with mode "' . $mode . '"');
            }
        }
        $this->setFormat(new CompleXml_Log_Format_Simple());
    }
    protected function _write ($log)
    {
        $line = $this->_format->build($log);
        if (false === @fwrite($this->_stream, $line)) {
            throw new CompleXml_Log_Exception('Can\'t write to stream');
        }
    }
    public function __destruct ()
    {
        if (is_resource($this->_stream)) {
            fclose($this->_stream);
        }
    }
}       