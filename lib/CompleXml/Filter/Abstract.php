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

/**
 *  Абстрактный класс для группы классов-фильтров данных
 */
abstract class CompleXml_Filter_Abstract
{
    abstract public function process ($value);
}