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
 * Класс проверяет строку на содержание только буквенно-циферных символов
 * @see CompleXml/Validate/Abstract.php
 */
#require_once 'CompleXml/Validate/Abstract.php';

class CompleXml_Validate_Alnum extends CompleXml_Validate_Abstract
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
            #require_once 'CompleXml/Filter/Alnum.php';
            self::$_filter = new CompleXml_Filter_Alnum();
            self::$_filter->setWithSpaces(true);
        }
        if ($valueString !== self::$_filter->process($valueString)) {
            $this->setError('Not alnum value');
            return false;
        }
        return true;
    }
}