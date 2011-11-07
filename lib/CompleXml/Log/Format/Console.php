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
 * Форматирование текста для вывода сообщений журнала в окно терминала
 */
class CompleXml_Log_Format_Console extends CompleXml_Log_Format_Abstract
    {
    private $_format;

    public function __construct ($format = null)
    {
        if (is_null($format)) {
            $this->_format = '%message% ' . PHP_EOL;
        }
    }

    public function build ($log)
    {
        switch ($log['type']) {
        case CompleXml_Log::EMERG:
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
                return '['.$log['typename'].']>'.$log['message'].PHP_EOL;
            }else{
                return PHP_EOL."\033[1;41;33m[".$log['typename'].']> '.$log['message']."\033[0m".PHP_EOL;
            }

            break;
        case CompleXml_Log::ALERT:
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
                return '['.$log['typename'].']>'.$log['message'].PHP_EOL;
            }else{
                return PHP_EOL."\033[1;41;37m[".$log['typename'].']> '.$log['message']."\033[0m".PHP_EOL;
            }
            break;
        case CompleXml_Log::CRIT:
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
                return '['.$log['typename'].']>'.$log['message'].PHP_EOL;
            }else{
                return PHP_EOL."\033[1;41;37m[".$log['typename'].']> '.$log['message']."\033[0m".PHP_EOL;
            }
            break;
        case CompleXml_Log::ERR:
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
                return $log['message'].PHP_EOL;
            }else{
                return "\033[1;31m".$log['message']."\033[0m".PHP_EOL;
            }
            break;
        case CompleXml_Log::WARN:
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
                return $log['message'].PHP_EOL;
            }else{
                return "\033[1;33m".$log['message']."\033[0m".PHP_EOL;
            }
            break;
        case CompleXml_Log::NOTICE:
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
                return $log['message'].PHP_EOL;
            }else{
                return "\033[1;37m".$log['message']."\033[0m".PHP_EOL;
            }
            break;
        case CompleXml_Log::INFO:
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
                return $log['message'].PHP_EOL;
            }else{
                return "\033[1;32m".$log['message']."\033[0m".PHP_EOL;
            }
            break;
        case CompleXml_Log::DEBUG:
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
                return '['.$log['typename'].']>'.$log['message'].PHP_EOL;
            }else{
                return "\033[36m[".$log['typename'].']> '.$log['message']."\033[0m".PHP_EOL;
            }
            break;
        default:
            return '['.$log['typename'].']>'.$log['message'].PHP_EOL;
            break;
        }
    }
}
