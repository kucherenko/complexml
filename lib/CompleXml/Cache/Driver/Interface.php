<?php

/**
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 */
interface CompleXml_Cache_Driver_Interface {
    public function __construct($options = array());
    public function set($id, $data, $lifeTime = false);
    public function get($id);
    public function delete($id);
    public function contains($id);
    public function flush();
}

