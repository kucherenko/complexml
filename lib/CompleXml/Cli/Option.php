<?php
/**
 * Console option object
 *
 * @author Andrey Kucehrenko
 */

 /**
  * Класс параметра коммандной строки
  */
class CompleXml_Cli_Option {
    
    private $_position = null;

    private $_value = null;

    private $_name;

    private $_message;

    private $_aliases = array();

    private $_requared = false;

    private $_default = null;

    private $_type = null;

    private $_is_parsed = false;

    public function __construct($name)
    {
        $this->_name = $name;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setHelp($message)
    {
        $this->_message = $message;
    }

    public function getHelp()
    {
        $name = $this->_name;
        if (!is_null($this->_type)){
            $name = $name.'=<'.$this->_type.'>';
        }
        $aliases = '';
        if (!empty ($this->_aliases)){
            $aliases = " (".implode(', ', $this->_aliases).")";
        }
        $required ='';
        if ($this->_requared){
            $required = ' (requared)';
        }
        $value = '';
        if (!is_null($this->_default)){
            $value = ' default:'.$this->_default;
        }

        return $name.$aliases.$value.$required."\t".$this->_message.PHP_EOL.PHP_EOL;
    }

    public function parse($argv)
    {
        $argv = (array) $argv;
        if (!is_null($this->_position)){
            if (isset($argv[$this->_position])&&($argv[$this->_position]{0}!='-')){
                try{
                    $this->setValue($argv[$this->_position]);
                    $this->_is_parsed = true;
                    return;
                }catch (CompleXml_Validate_Exception $e){
                    throw new CompleXml_Cli_Exception('Not valid argument');
                }
            }
        }
        
        foreach ($argv as $key=>$variable) {
            $pair = explode("=", $variable);
            if (($pair[0]==$this->_name)||in_array($pair[0], $this->_aliases)){
                try{
                    if (isset($pair[1])){
                        $this->setValue($pair[1]);
                    }elseif(isset($argv[$key+1])&&$argv[$key+1]{0}!='-'){
                         $this->setValue($argv[$key+1]);
                    }else{
                        $this->setValue(true);
                    }
                    $this->_is_parsed = true;
                }catch (CompleXml_Validate_Exception $e){
                    
                    throw new CompleXml_Cli_Exception('Not valid argument');
                }
                return;
            }
        }
        if (($this->_requared)&&is_null($this->getValue()))
        {
            throw new CompleXml_Cli_Exception('Option is required');
        }
        
    }

    public function setAliases($aliases)
    {
        $this->_aliases = (array) $aliases;
    }

    public function getAliases()
    {
        return $this->_aliases;
    }

    public function setRequired($flag)
    {
        $this->_requared = $flag;
    }

    public function setType($type)
    {
        $value = $this->_value;
        if (!($type instanceof CompleXml_Validate_Abstract)&&is_string($type)){
            $class_name = 'CompleXml_Validate_'.$type;
            if (!class_exists($class_name)){
                $is_loaded = CompleXml_Loader::autoload($class_name);
            }else{
                $is_loaded = true;
            }
            if (!$is_loaded){
                throw new CompleXml_Validate_Exception('Can\'t find validate class');
            }
            $type = new $class_name;
        }elseif (!($type instanceof CompleXml_Validate_Abstract)&&!is_null($type)){
            throw new CompleXml_Validate_Exception('Can\'t must be instanse of CompleXml_Validate_Abstract class or string');
        }
        $this->_type = $type;
        if (!is_null($value)){
            $this->setValue($value);
        }
    }

    public function getValue()
    {
        if (is_null($this->_value)){
            return $this->_default;
        }
        return $this->_value;
    }

    public function setDefault($value)
    {
        if (!is_null($this->_type)&&!is_null($value)){
            if (!$this->_type->isValid($value)){
                throw new CompleXml_Validate_Exception('Option value not valided');
            }
        }
        $this->_default = $value;
    }

    public function getDefault()
    {
        return $this->_default;
    }

    public function getPosition()
    {
        return $this->_position;
    }
    
    public function setPosition($position)
    {
        $this->_position = (int) $position;
    }

    public function setValue($value)
    {
        if (!is_null($this->_type)&&!is_null($value)){
            if (!$this->_type->isValid($value)){
                throw new CompleXml_Validate_Exception('Option value not valided');
            }
        }
        if ((!$this->_is_parsed)&&!is_null($value)){
            $this->_default = $value;
        }else{
            $this->_value = $value;
        }
    }
}

