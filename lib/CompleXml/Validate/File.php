<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Validate
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * @see CompleXml/Validate/Abstract.php
 */
#require_once 'CompleXml/Validate/Abstract.php';
/**
 * Проверяет, является ли строка путем к файлу
 */
class CompleXml_Validate_File extends CompleXml_Validate_Abstract
{
    public function isValid ($value)
    {
        return is_file($value);
    }
}