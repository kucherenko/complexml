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
 * Класс для работы с конфигурацией
 */
final class CompleXml_Config
{
    private static $_config;

    static public function set ($name, $value)
    {
        if (isset(self::$_config[ $name ]) && is_array(self::$_config[ $name ]) && is_array($value)) {
            self::$_config[ $name ] = array_merge(self::$_config[ $name ], $value);
        } else {
            self::$_config[ $name ] = $value;
        }
    }

    static public function setArray ($array)
    {
        foreach ($array as $key=>$value) {
            self::set($key, $value);
        }

    }
    /**
     * @static
     * @throws CompleXml_Config_Exception
     * @param $name 
     * @return mixed
     */
    static public function get ($name)
    {
        if (! isset(self::$_config[ $name ])) {
            throw new CompleXml_Config_Exception('Option "'.$name.'" not found in config');
        }
        return self::$_config[ $name ];
    }
    
    static public function read ($filename, CompleXml_Config_Reader_Abstract $config_reader)
    {
        $config_reader->read_file($filename);
    }

    public static function readComponentSettings($name)
    {
        if (strpos($name, 'CompleXml_')!==0){
            $name = 'CompleXml_'.$name;
        }
        if (isset(self::$_config[$name])){
            $settings = self::$_config[$name];
        }
        $component_path = explode('_',  $name);
        $component_path[0] = 'CompleXmlData';
        $filename = dirname(dirname(__FILE__)). DIRECTORY_SEPARATOR .implode('/', $component_path).'.php';
        if (file_exists($filename)){
            $default_settings = (array) include $filename;
            self::set($name, $default_settings);
        }
        if (isset($settings)){
            self::set($name, $settings);
        }
        if (isset(self::$_config[$name])) {
            return self::$_config[$name];
        } else {
            return null;
        }

    }

    public static function setSettings($component, $params)
    {
        if (strpos($component, 'CompleXml_')!==0){
            $component = 'CompleXml_'.$component;
        }
        if (!isset(self::$_config[$component])){
            self::readComponentSettings($component);
        }
        $value = (array) $params;
        self::set($component, $value);
    }

    public static function getSettings($component, $param_name)
    {
        if (strpos($component, 'CompleXml_')!==0){
            $component = 'CompleXml_'.$component;
        }
        if (!isset(self::$_config[$component])){
            self::readComponentSettings($component);
        }
        return @self::$_config[$component][$param_name];
    }

    public function __construct ()
    {
        throw new CompleXml_Config_Exception('CompleXml_Config can\'t be created as object');
    }
} 