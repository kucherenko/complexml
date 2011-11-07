<?php
/**
 * CompleXml_Cache_Criteria_Interface
 *
 * @author Andrey Kucehrenko (kucherenko.andrey@gmail.com)
 */
interface CompleXml_Cache_Criteria_Interface {
    public function __construct(CompleXml_Cache_Driver_Interface $driver, $options);
    public function save();
    public function isValid();
    public function flush();
}