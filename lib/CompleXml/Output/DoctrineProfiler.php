<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Output
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * Класс для протоколирования запросов Doctrine
 */
class CompleXml_Output_DoctrineProfiler implements CompleXml_Output_Interface {

    public static function execute(CompleXml_Controller $ControllerObject)
    {
        $profiler = Doctrine_Manager::connection()->getListener();
        if ($profiler instanceof Doctrine_Connection_Profiler)
        {
            $time = 0;
            $ControllerObject->View->startElement('doctrine-debugger');
            foreach ($profiler as $event) {
                $time += $event->getElapsedSecs();
                $ControllerObject->View->startElement('event');
                $ControllerObject->View->writeElement('time', $event->getElapsedSecs());
                $ControllerObject->View->writeElement('name', $event->getName());
                $ControllerObject->View->writeElement('query', $event->getQuery());
                $params = $event->getParams();
                $ControllerObject->View->writeElement('params', var_export($params, true));
                $ControllerObject->View->endElement();
            }
            $ControllerObject->View->writeElement('total-time', $time);
            $ControllerObject->View->endElement();
        }
        return false;
    }
}