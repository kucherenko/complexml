<?php
/**
 * Exit from current command line session
 * 
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 * @version 0.3
 * @task exit
 */
class CompleXml_Cli_Task_Exit extends CompleXml_Cli_Task {

    public function  __construct()
    {
        CompleXml_Log::info('Buy!');
        die();
    }
}


