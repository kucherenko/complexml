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
 * Прверяет, является ли строка e-mail
 * @see CompleXml/Validate/Abstract.php
 */
#require_once 'CompleXml/Validate/Abstract.php';
class CompleXml_Validate_Email extends CompleXml_Validate_Abstract
{
    public function isValid ($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL)!==false;
    }
}