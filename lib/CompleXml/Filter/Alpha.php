<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Filter
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

#require_once 'CompleXml/Filter/Abstract.php';
/**
 * Вырезает из строки все, что не является буквенным символом
 */
class CompleXml_Filter_Alpha extends CompleXml_Filter_Abstract
{
    /**
     * Flag for spaces in string
     *
     * @var boolean
     */
    private $_with_spaces = false;
    /**
     * @return boolean
     */
    public function getWithSpaces ()
    {
        return $this->_with_spaces;
    }
    /**
     * @param boolean $with_spaces
     */
    public function setWithSpaces ($with_spaces)
    {
        $this->_with_spaces = $with_spaces;
    }
    public function process ($value)
    {
        $whiteSpace = $this->getWithSpaces() ? '\s' : '';
        // Unicode safe filter for the value with mbstring
        $pattern = '/[^[:alpha:]' . $whiteSpace . ']/u';
        return preg_replace($pattern, '', (string) $value);
    }
}