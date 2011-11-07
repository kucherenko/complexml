<?php
/**
 * CompleXml_Driver_Xcache class
 *
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 */
class CompleXml_Cache_Driver_Xcache implements CompleXml_Cache_Driver_Interface{

    public function __construct($options = array())
    {
        if (!extension_loaded('xcache')){
            throw new CompleXml_Cache_Exception('XCache extension not loaded');
        }
    }

    public function set($id, $data, $lifeTime = false)
    {
        return xcache_set( $id, $data, $lifeTime );
    }

    public function get($id)
    {
        if ($this->contains($id)){
            return xcache_get($id);
        }else{
            return null;
        }
    }

    public function delete($id)
    {
        return xcache_unset($id);
    }

    public function contains($id){
        return xcache_isset($id);
    }

    public function flush()
    {
        xcache_clear_cache(XC_TYPE_VAR, 0);
    }
}

