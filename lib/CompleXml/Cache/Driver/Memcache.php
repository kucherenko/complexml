<?php
/**
 * CompleXml_Driver_Memcache class
 *
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 */
class CompleXml_Cache_Driver_Memcache implements CompleXml_Cache_Driver_Interface{

    /**
     * @var array
     */
    private $_options;
    /**
     * @var Memcache
     */
    private $_memcache = null;

    public function __construct($options = array())
    {
        if (!extension_loaded('memcache')){
            throw new CompleXml_Cache_Exception('Memcache extension not loaded');
        }
        $this->_options = $options;
        $servers = (array) $this->_options['servers'];
        $this->_memcache = new Memcache();
        foreach ($servers as $server) {
            if (!isset($server['host'])){
                continue;
            }
            if ( !isset($server['persistent'])) {
                $server['persistent'] = true;
            }
            if ( !isset($server['port'])) {
                $server['port'] = 11211;
            }
            $this->_memcache->addServer($server['host'], $server['port'], $server['persistent']);
        }

    }

    public function set($id, $data, $lifeTime = false)
    {
        if ($this->_options['compressed']) {
            $flag = MEMCACHE_COMPRESSED;
        } else {
            $flag = 0;
        }

        return $this->_memcache->set( $id, $data, $flag, $lifeTime);
    }

    public function get($id)
    {
        return $this->_memcache->get($id);
    }

    public function delete($id)
    {
        return $this->_memcache->delete($id);
    }

    public function contains($id)
    {
        return (boolean) $this->_memcache->get($id);;
    }

    public function flush()
    {
        $this->_memcache->flush();
    }
}

