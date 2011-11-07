<?php
/**
 * Doctrine Command Line Interface
 * Usage: doctrine [action-name] [parametrs...]
 * for more information about doctrine task use ./cx doctrine
 *
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 * @version 0.3
 * @task doctrine
 * @task orm
 */
class CompleXml_Cli_Task_Doctrine extends CompleXml_Cli_Task {

    /**
     * Doctrine Command Line Interface action
     *
     * @action * 
     * @example doctrine build-all
     */
    public function main($task = null) {
        try{
            $config = CompleXml_Config::get('doctrine_config');
        }catch(CompleXml_Config_Exception $e){
            CompleXml_Log::err($e->getMessage());
        }
        $conn = Doctrine_Manager::connection();
        CompleXml_Log::notice("Doctrine version: ".Doctrine::VERSION);
        $cli = new Doctrine_Cli($config);
        $cli->run(array_slice($this->argv, 1));
        $cli->loadTasks();
    }
}

