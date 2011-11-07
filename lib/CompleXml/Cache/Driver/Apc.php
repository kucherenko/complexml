<?php
/**
 * CompleXml_Driver_Apc class
 *
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 */

class CompleXml_Cache_Driver_Apc implements CompleXml_Cache_Driver_Interface{

    public function __construct($options = array())
    {
        if (!extension_loaded('apc')){
            throw new CompleXml_Cache_Exception('APC extension not loaded');
        }

    }

    public function set($id, $data, $lifeTime = false)
    {
        return (boolean) apc_store( $id, $data, $lifeTime );
    }

    public function get($id)
    {
        $result = apc_fetch($id);
        if ($result!==false){
            return $result;
        }else{
            return null;
        }
    }

    public function delete($id)
    {
        return apc_delete($id);
    }

    public function contains($id)
    {
        return apc_fetch($id) === false ? false : true;
    }

    public function flush()
    {
        
    }
}

