<?php
/**
 * CompleXml_Driver_File class
 *
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 */
class CompleXml_Cache_Driver_File implements CompleXml_Cache_Driver_Interface{
    
    private $_options = array();

    public function __construct($options = array())
    {
        $this->_options = $options;
        if (!isset($this->_options['dirname'])){
            throw new CompleXml_Cache_Exception('Set dirname option for file cache');
        }
    }

    public function set($id, $data, $lifeTime = false)
    {
        $filename = $this->_getPath($id);
        $dirname = dirname($filename);
        $current_umask = umask();
        umask(0000);
        if (!file_exists($dirname)){
            $is_crated = mkdir($dirname, 0777, true);
            if (!$is_crated){
                throw new CompleXml_Cache_Exception( 'Can\'t create cache dir '.$dirname );
            }
        }
        file_put_contents($filename, $data);
        chmod($filename, 0666);
        umask($current_umask);
    }

    public function get($id)
    {
        $filename = $this->_getPath($id);
        return @file_get_contents($filename);
    }

    public function delete($id)
    {
        $filename = $this->_getPath($id);
        unlink($filename);
    }

    public function contains($id){
        $filename = $this->_getPath($id);
        return file_exists($filename);
    }

    public function flush()
    {
        $this->_deleteDirectory($this->_options['dirname']);
    }
        
    private function _deleteDirectory($dir)
    {
        if (!file_exists($dir)){
            return true;  
        }
        if (!is_dir($dir)){
            $is_deleted = unlink($dir);
            if (!$is_deleted){
                throw new CompleXml_Cache_Exception( 'Can\'t clear cache, can\'t delete "'.$dir.'"' );
            }
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..'){
              continue;  
            }
            $this->_deleteDirectory($dir.DIRECTORY_SEPARATOR.$item);
        }
        if ($dir!=$this->_options['dirname']){
            $is_deleted = rmdir($dir);
            if (!$is_deleted){
                throw new CompleXml_Cache_Exception( 'Can\'t clear cache, can\'t delete "'.$dir.'"' );
            }
        }
    }

    private function _getPath($id)
    {
        $res = md5($id);
        return $this->_options['dirname'].DIRECTORY_SEPARATOR.$res{0}.DIRECTORY_SEPARATOR.$res{1}.DIRECTORY_SEPARATOR.$res;
    }
}

