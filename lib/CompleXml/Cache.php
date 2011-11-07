<?php
/**
 * CompleXml_Cache
 *
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 */
class CompleXml_Cache {

    /**
     * @var CompleXml_Caoche_Driver_Interface
     */
    private $_driver;

    public function  __construct( CompleXml_Cache_Driver_Interface $driver)
    {
        $this->_driver = $driver;
    }

    public function set($id, $data, $lifetime = false, $tags = array(), $criterias = array())
    {
        $CacheItem = new CompleXml_Cache_Item($id, $data);
        if ($lifetime!==false){
            $CriteriaLifetime = new CompleXml_Cache_Criteria_Lifetime($this->_driver, array('time'=>$lifetime));
            $CriteriaLifetime->save();
            $CacheItem->addCriteria($CriteriaLifetime);
        }
        $tags = (array) $tags;
        foreach ($tags as $tag) {
            $CriteriaTag = new CompleXml_Cache_Criteria_Tag($this->_driver, array('name'=>$tag));
            $CriteriaTag->save();
            $CacheItem->addCriteria($CriteriaTag);
        }
        foreach ($criterias as $Criteria) {
            if ($Criteria instanceof CompleXml_Cache_Criteria_Interface){
                $Criteria->save();
                $CacheItem->addCriteria($Criteria);
            }
        }
        return $this->_driver->set($CacheItem->getId(), serialize($CacheItem));
    }

    public function get($id)
    {
        $CacheItem = unserialize($this->_driver->get($id));
        if (empty($CacheItem)||!($CacheItem instanceof CompleXml_Cache_Item)){
            return false;
        }
        if ($CacheItem->isValid()){
            return $CacheItem->getData();
        }
        $this->_driver->delete($id);
        return false;
    }

    public function flush($criterias = array())
    {
        if (!empty($criterias)){
            $criterias = (array) $criterias;
            foreach ($criterias as $Criteria) {
                
                if ($Criteria instanceof CompleXml_Cache_Criteria_Interface){
                    $Criteria->flush();
                }
            }
        }else{
            $this->_driver->flush();
        }
    }
}
