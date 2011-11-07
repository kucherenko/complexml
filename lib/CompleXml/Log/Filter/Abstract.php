<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Log_Filter
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

 /**
  * Абстрактный класс для фильтрации сообщений, попадающих в журнал
  */
abstract class CompleXml_Log_Filter_Abstract
{
     abstract public function accept($log);   
}