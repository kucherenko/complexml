<?php

/**
 * Getopt object
 *
 * @author Andrey Kucherenko
 */

/**
 * Класс для получения параметров коммандной строки
 */
class CompleXml_Cli_Getopt {

    private $_options = array();

    private $_aliases = array();

    private $_help;

    public function  __construct($options = array()) {
        $this->init($options);
    }

    public function init($options)
    {
        foreach ($options as $name => $option) {
            if ($option instanceof CompleXml_Cli_Option){
                $this->setOption($option);
            }elseif (is_array($option) && is_string($name)){
                if (isset($this->_options[$name])){
                    throw new CompleXml_Cli_Exception('Option name already used');
                }
                $newoption = new CompleXml_Cli_Option($name);
                if (isset ($option['aliases'])){
                    $newoption->setAliases($option['aliases']);
                }
                if (isset ($option['type'])){
                    $newoption->setType($option['type']);
                }
                if (isset ($option['position'])){
                    $newoption->setPosition($option['position']);
                }
                if (isset ($option['help'])){
                    $newoption->setHelp($option['help']);
                }
                if (isset ($option['value'])){
                    $newoption->setValue($option['value']);
                }
                if (isset ($option['default'])){
                    $newoption->setValue($option['default']);
                }
                if (isset ($option['required'])&&($option['required']===true)){
                    $newoption->setRequired(true);
                }
                $this->setOption($newoption);
            }
        }
    }

    public function parse($arguments)
    {
        foreach ($this->_options as $option){
            $option->setValue(null);
            $option->parse($arguments);
        }
    }

    private function _setAliases(CompleXml_Cli_Option $option)
    {
        $name = $option->getName();
        if (isset ($this->_aliases[$name])){
            throw new CompleXml_Cli_Exception('Option alias already used');
        }
        $this->_aliases[$name] = $option;
        $aliases = (array) $option->getAliases();
        foreach ($aliases as $alias) {
            if (isset ($this->_aliases[$alias])){
                throw new CompleXml_Cli_Exception('Option alias already used');
            }
            $this->_aliases[$alias] = $option;
        }

    }

    public function getCount()
    {
        return count($this->_options);
    }

    public function setHelp($message)
    {
        $this->_help = $message;
    }

    public function getHelp()
    {

        $options = $this->getOptions();
        $res =$this->_help.PHP_EOL.PHP_EOL;
        foreach ($options as $option) {
            $res.=$option->getHelp();
        }
        return $res;
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function getOption($name)
    {
        if (isset($this->_aliases[$name])){
            return $this->_aliases[$name];
        }
        return null;
    }

    public function setOption(CompleXml_Cli_Option $option)
    {
        if (isset($this->_options[$option->getName()])){
            throw new CompleXml_Cli_Exception('Option name already used');
        }
        $this->_options[$option->getName()] = $option;
        $this->_setAliases($option);
    }

    public function getValue($name)
    {
        $option = $this->getOption($name);
        if (!is_null($option)){
            return $option->getValue();
        }
        return null;

    }
}