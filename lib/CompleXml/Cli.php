<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Cli
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * CompleXml_Cli class
 */
class CompleXml_Cli {

    /**
     * Run cli application
     * @param array $argv
     */
    public static function run($argv) {
        
        $settings = CompleXml_Config::readComponentSettings(__CLASS__);

        $tasks = (array) $settings['tasks'];

        foreach ($tasks as $task) {
            if (!class_exists($task)) {
                CompleXml_Loader::autoload($task);
            }
        }

        $Getopt = new CompleXml_Cli_Getopt($settings['options']);
        $Getopt->parse($argv);
        $workspace_reflection = new CompleXml_Reflection_Workspace();

        $task = $workspace_reflection->getClass('CompleXml_Cli_Task',
                array('task'=>strtolower($Getopt->getValue('--task')))
        );

        if (is_null($task)) {
            throw new CompleXml_Cli_Exception('Task "'.$Getopt->getValue('--task').'" not found');
        }

        $task_params = CompleXml_Reflection_Annotations::getParametrs($task->getAnnotations(),
                array('task'=>strtolower($Getopt->getValue('--task')))
        );

        $task_name = $task->getName();
        $TaskObject = new $task_name($argv);
        $TaskObject->init();
        $action = $task->getMethodByAnnotation(array('action'=>strtolower($Getopt->getValue('--action'))));
        if (is_null($action)&&(!is_null($Getopt->getValue('--action')))) {
            throw new CompleXml_Cli_Exception('Action "'.$Getopt->getValue('--action').'" not found at "'.$task_name.'"');
        }elseif (!is_null($action)) {
            $action_params = CompleXml_Reflection_Annotations::getParametrs($action->getAnnotations(),
                    array('action'=>strtolower($Getopt->getValue('--action')))
            );
            $action_name = $action->getName();
            $listeners = array();
            $listeners_name = (array) $settings['listeners'];
            foreach ($listeners_name as $listener) {
                $ListenerObject = new $listener($TaskObject);
                $ListenerObject->beforeAction($action_name);
                $listeners[] = $ListenerObject;
            }
            $params = array_merge($task_params, $action_params);
            $args = $action->getParameters();
            $_args = array();
            foreach ($args as $param) {
                $_args[] = @$params[$param->getName()];
            }
            call_user_func_array(array($TaskObject, $action_name), $_args);
            foreach ($listeners as $ListenerObject) {
                $ListenerObject->afterAction($action_name);
            }
        }
    }
}