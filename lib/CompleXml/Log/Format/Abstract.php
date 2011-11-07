<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Log_Format
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

 /**
  * Абстракный класс, общий для всех класов, форматирующих сообщения до записи в журнал
  */
abstract class CompleXml_Log_Format_Abstract
{
    abstract protected function build($log);
     
}