<?php
/**
 * CompleXml_Cache_Criteria_Lifetime
 *
 * @author Andrey Kucehrenko (kucherenko.andrey@gmail.com)
 */
class CompleXml_Cache_Criteria_Lifetime implements CompleXml_Cache_Criteria_Interface {
    /**
     * @var CompleXml_Cache_Driver_Interface
     */
    private $_driver;
    /**
     * Cache end date 
     * @var int
     */
    private $_time = null;

    private $_options = array();

    public function  __construct(CompleXml_Cache_Driver_Interface $driver, $options) {
        $this->_driver = $driver;
        $this->_options = $options;
    }

    public function save()
    {
        $time = (int) $this->_options['time'];
        if ($time == 0){
            return false;
        }
        $time = time() + $time;
        $this->_time = $time;
    }

    public function isValid()
    {
        if (time()<=$this->_time){
            return true;
        }
        return false;
    }

    public function flush()
    {
        
    }
}
