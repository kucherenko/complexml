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
 * Проверяет, является ли строка директорией
 * @see CompleXml/Validate/Abstract.php
 */
#require_once 'CompleXml/Validate/Abstract.php';
class CompleXml_Validate_Dir extends CompleXml_Validate_Abstract
{
    public function isValid ($value)
    {
        return is_dir($value);
    }
}