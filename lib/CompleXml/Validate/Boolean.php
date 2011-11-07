<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Validate
 * @copyright  Copyright (c) 2010 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * @see CompleXml/Validate/Abstract.php
 */
/**
 * Validate is value boolean
 */
class CompleXml_Validate_Boolean extends CompleXml_Validate_Abstract
{
    
    public function isValid ($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

}