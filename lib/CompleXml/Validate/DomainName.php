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
 * Проверяет является ли строка доменом
 * @see CompleXml/Validate/Abstract.php
 */
#require_once 'CompleXml/Validate/Abstract.php';
class CompleXml_Validate_DomainName extends CompleXml_Validate_Abstract
{
    public function isValid ($value)
    {
        $pattern = "/^([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,6})$/i";
        if (preg_match($pattern, $value)) {
            return true;
        }
        return false;
    }
}