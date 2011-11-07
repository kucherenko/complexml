<?php
/**
 * CompleXml_Cache_Criteria_Tag
 *
 * @author Andrey Kucehrenko (kucherenko.andrey@gmail.com)
 */
class CompleXml_Cache_Criteria_Tag implements CompleXml_Cache_Criteria_Interface {
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
        if (!isset($this->_options['name'])||empty($this->_options['name'])){
            throw new CompleXml_Cache_Exception('Bad Tag cache criteria name');
        }
        $this->_options['name'] = (string) $options['name'];
    }

    public function save()
    {
        $this->_time = time();
    }

    public function isValid()
    {
        $criteria = (array) $this->_driver->get('CompleXml_Cache_Criteria_Tag_'.$this->_options['name']);
        
        if (!isset($criteria['time'])){
            return true;
        }
        if ($criteria['time'] <= $this->_time){
            return true;
        }
        return false;
    }

    public function flush()
    {
        $this->_driver->set('CompleXml_Cache_Criteria_Tag_'.$this->_options['name'], array('time'=>time()));
    }

}
