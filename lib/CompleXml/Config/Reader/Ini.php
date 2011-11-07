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
#require_once 'CompleXml/Config/Reader/Abstract.php';
/**
 * Класс, для вычитывания конфигурации из ini файлов
 */
class CompleXml_Config_Reader_Ini extends CompleXml_Config_Reader_Abstract
{
     protected function _read($filename)
     {
         $ini = parse_ini_file($filename, true);
         foreach ($ini as $section=>$value) {
         	CompleXml_Config::set($section, $value);
         }
     }   
}