<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Loader
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * Class for load framework components
 */
final class CompleXml_Loader {

    private static $_path = null;
    /**
     * autoload
     *
     * simple autoload function
     * returns true if the class was loaded, otherwise false
     *
     * @param string $classname
     * @return boolean
     */
    public static function autoload($className) {
        self::getLibraryPath();
        $class = self::$_path . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        if (file_exists($class)) {
            include $class;
            return true;
        }
        if (defined('APPLICATION_HOME')){
            $class = APPLICATION_HOME . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
            if (file_exists($class)) {
                include $class;
                return true;
            }
        }
        return false;
    }

    public static function getLibraryPath()
    {
        if (is_null(self::$_path)){
            self::$_path = dirname(dirname(__FILE__));
        }
        return self::$_path;
    }
}

