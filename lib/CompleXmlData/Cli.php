<?php
$tasks = array(
    'CompleXml_Cli_Task_Help',
    'CompleXml_Cli_Task_Cache',
    'CompleXml_Cli_Task_Create',
    'CompleXml_Cli_Task_Exit'
);
if (class_exists('Doctrine') || class_exists('Doctrine_Manager')) {
    $tasks[] = 'CompleXml_Cli_Task_Doctrine';
}
return array(
    'options' => array(
        '--task' => array(
            'type' => 'String',
            'position' => 1
        ),
        '--action' => array(
            'type' => 'String',
            'position' => 2
        )
    ),
    'listeners' => array('CompleXml_Listener_Behaviour'),
    'tasks' => $tasks
);