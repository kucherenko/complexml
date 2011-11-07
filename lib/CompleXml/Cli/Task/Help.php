<?php
/**
 * Show help of all tasks
 * --full, -f option allow to show full information about tasks
 * Usage: help [task-name] [help parametrs...]
 * 
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 * @version 0.3
 * @task help
 * @task ?
 */
class CompleXml_Cli_Task_Help extends CompleXml_Cli_Task {

    public function  __construct($argv) {
        $options = array(
            '--full'=>array(
            'aliases'=>array('-f'),
            )
        );
        parent::__construct($argv, $options);
    }
    /**
     * Default task, show help of tasks
     * @example help
     * @example ?
     * @example help <task:w>
     * @example help project
     * @action <task:w>
     * @action *
     */
    public function show($task = null) {
        $WorkspaceRef = new CompleXml_Reflection_Workspace();
        $params = array();
        $is_full = $this->Getopt->getValue('-f');
        if (!is_null($task)) {
            $params['task'] = $task;
            $is_full = true;
        }
        $tasks = $WorkspaceRef->getClasses('CompleXml_Cli_Task', $params);
        if (empty($tasks)){
            throw new CompleXml_Cli_Exception('Not found task "'.$params['task'].'"');
        }
        $is_first = true;
        foreach ($tasks as $TaskRef) {
            $annotations = $TaskRef->getAnnotations();
            if(is_null($annotations['task'])) {
                CompleXml_Log::err('Task class '.$TaskRef->getName().' without @task annotation');
                continue ;
            }
            if (is_array($annotations['task'])) {
                $annotations['task'] = implode(', ', $annotations['task']);
            }
            if ($is_first){
                CompleXml_Log::notice(PHP_EOL.'Tasks:');
                $is_first = false;
            }
            CompleXml_Log::notice($annotations['task']);
            CompleXml_Log::info("\t".str_replace(PHP_EOL, PHP_EOL."\t", $TaskRef->getDescription()));
            if ($is_full) {
                if (isset ($annotations['author'])) {
                    CompleXml_Log::info("\tAuthor: ".$annotations['author']);
                }
                if (isset ($annotations['version'])) {
                    CompleXml_Log::info("\tVersion: ".$annotations['version']);
                }
            }
            $actions = $TaskRef->getCXMethods(ReflectionMethod::IS_PUBLIC);
            $is_first_action = true;
            foreach ($actions as $ActionRef) {
                $action_annotation = $ActionRef->getAnnotations();
                if (!isset($action_annotation['action'])) {
                    continue;
                }
                if ($is_first_action){
                    CompleXml_Log::notice("\tActions:");
                    $is_first_action = false;
                }
                if (is_array($action_annotation['action'])) {
                    $action_annotation['action'] = implode(', ', $action_annotation['action']);
                }
                CompleXml_Log::notice("\t".$action_annotation['action']);
                CompleXml_Log::info("\t\t".str_replace(PHP_EOL, PHP_EOL."\t\t", $ActionRef->getDescription()));
                if ($is_full) {
                    if (isset ($action_annotation['example'])) {
                        if (is_array($action_annotation['example'])) {
                            $action_annotation['example'] = implode("; ", $action_annotation['example']);
                        }
                        CompleXml_Log::warn("\t\texample: ".$action_annotation['example']);
                    }
                }
            }
        }
    }

    public static function completion() {
        $WorkspaceRef = new CompleXml_Reflection_Workspace();
        $result = array();
        $tasks = $WorkspaceRef->getClasses('CompleXml_Cli_Task', $params);
        foreach ($tasks as $TaskRef) {
            $annotations = $TaskRef->getAnnotations();
            if(is_null($annotations['task'])) {
                continue ;
            }
            if (is_array($annotations['task'])){
                foreach ($annotations['task'] as $task) {
                    $result[$task] = $task;
                }
            }else{
                $result[$annotations['task']] = $annotations['task'];
            }
        }
        return $result;
    }
}

