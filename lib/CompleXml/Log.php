<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Log
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * Класс для работы с журналами
 */
class CompleXml_Log {
    const EMERG = 0;
    const ALERT = 1;
    const CRIT = 2;
    const ERR = 3;
    const WARN = 4;
    const NOTICE = 5;
    const INFO = 6;
    const DEBUG = 7;
    private static $_writers = array();
    private static $_typenames = array();

    static public function add ($message, $type) {
        if (empty(self::$_typenames)) {
            $r = new ReflectionClass('CompleXml_Log');
            self::$_typenames = array_flip($r->getConstants());
        }
        $log = array('date' => date('c') , 'message' => $message , 'type' => $type , 'typename' => self::$_typenames[$type]);
        foreach (self::$_writers as $writer) {
            $writer->write($log);
        }
    }

    static public function emerg($message) {
        self::add($message, CompleXml_Log::EMERG);
    }

    static public function alert($message) {
        self::add($message, CompleXml_Log::ALERT);
    }

    static public function crit($message) {
        self::add($message, CompleXml_Log::CRIT);
    }

    static public function err($message) {
        self::add($message, CompleXml_Log::ERR);
    }

    static public function warn($message) {
        self::add($message, CompleXml_Log::WARN);
    }

    static public function notice($message) {
        self::add($message, CompleXml_Log::NOTICE);
    }

    static public function info($message) {
        self::add($message, CompleXml_Log::INFO);
    }
    
    static public function debug($message) {
        self::add($message, CompleXml_Log::DEBUG);
    }

    static public function addWriter (CompleXml_Log_Writer_Abstract $writer) {
        self::$_writers[] = $writer;
    }
}