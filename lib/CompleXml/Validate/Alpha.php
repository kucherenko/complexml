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
 * Проверяет на наличие только буквенных символов в строке
 * @see CompleXml/Validate/Abstract.php
 */
#require_once 'CompleXml/Validate/Abstract.php';
class CompleXml_Validate_Alpha extends CompleXml_Validate_Abstract
{
    protected static $_filter = null;
    public function isValid ($value)
    {
        if (is_null($value)) {
            $this->setError('Value is null');
            return false;
        }
        $valueString = (string) $value;

        if (is_null(self::$_filter)) {
            #require_once 'CompleXml/Filter/Alpha.php';
            self::$_filter = new CompleXml_Filter_Alpha();
            self::$_filter->setWithSpaces(true);
        }
        if ($valueString !== self::$_filter->process($valueString)) {
            $this->setError('Not alpha value');
            return false;
        }
        return true;
    }
}