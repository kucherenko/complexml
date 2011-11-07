<?php
/**
 * Cache manager
 * 
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 * @version 0.3
 * @task cache
 */
class CompleXml_Cli_Task_Cache extends CompleXml_Cli_Task {

    /**
     * Clear cache
     * @example cache clear
     * @example cache clear:Router
     * @action clear
     * @action clear:<tag:w>
     */
    public function clear($tag = null)
    {
        $cache_driver = CompleXml_Config::getSettings('Application', 'cache_driver');
        if (($cache_driver instanceof CompleXml_Cache_Driver_Apc)||($cache_driver instanceof CompleXml_Cache_Driver_Xcache)){
            throw new CompleXml_Cli_Exception(get_class($cache_driver).' not supported in command line');
        }
        if (!is_null($tag)){
            $TagCriteria = new CompleXml_Cache_Criteria_Tag($cache_driver, array('name'=>$tag));
            $TagCriteria->flush();
            CompleXml_Log::info('Cache with tag "'.$tag.'" cleared');
        }else{
            $cache_driver->flush();
            CompleXml_Log::info('Cache cleared');
        }
    }
}


