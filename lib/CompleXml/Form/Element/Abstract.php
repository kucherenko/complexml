<?php

/**
 * Abstract class of form element
 *
 * @author Andrey Kucherenko
 */
abstract class CompleXml_Form_Element_Abstract implements CompleXml_Form_Interface
{

    protected $_name = null;
    protected $_formname = null;
    protected $_value = null;
    protected $_label = null;
    protected $_attributes = array();
    protected $_errors = array();
    protected $_validators = array();
    
    public function  __construct($name = null, $value = null, $label = null)
    {
        if (!is_null($name)) {
            $this->setName($name);
        }
        if (!is_null($value)){
            $this->setValue($value);
        }
        if (!is_null($label)){
            $this->setLabel($label);
        }
        $this->init();
    }

    public function init()
    {

    }
    
    public function addError($error)
    {
        $this->_errors[] = $error;
        return $this;
    }
    
    public function getErrors()
    {
        return $this->_errors;
    }
    
    public function hasErrors()
    {
        return (boolean) count($this->_errors);
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }
    
    public function getLabel()
    {
        return $this->_label;
    }
    
    public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }
    
    public function getValue()
    {
        return $this->_value;
    }
    
    public function setValue($value)
    {
        $this->_value = $value;
        return $this;
    }

    public function setValues($values)
    {
        if (isset($values[$this->getName()])){
            $this->setValue($values[$this->getName()]);
        }
        return $this;
    }

    public function getParentFormName()
    {
        return $this->_formname;
    }
    
    public function setParentFormName($name)
    {
        $this->_formname = $name;
        return $this;
    }
    
    public function addAttribute($name, $value)
    {
        $this->_attributes[$name] = $value;
        return $this;
    }
    
    public function removeAttribute($name)
    {
        unset($this->_attributes[$name]);
        return $this;
    }
    
    public function setAttributes($array)
    {
        $this->_attributes = (array) $array;
        return $this;
    }
    
    public function getAttribute($name)
    {
        if (isset($this->_attributes[$name])) {
            return $this->_attributes[$name];
        }
        return null;
    }
    
    public function getAttributes()
    {
        return (array) $this->_attributes;
    }
    
    public function addValidator(CompleXml_Validate_Abstract $validator, $error_message = null)
    {
        $this->_validators[] = array('validator'=>$validator, 'message'=>$error_message);
        return $this;
    }
    
    public function flushValidators()
    {
        $this->_validators = array();
        return $this;
    }

    public function hasValidators()
    {
        return (boolean) count($this->_validators);
    }

    public function isValid($value)
    {
        $this->setValue($value);
        foreach ($this->_validators as $item) {
            if (isset($item['validator'])) {
                if (!$item['validator']->isValid($this->getValue())) {
                    $this->addError($item['message']);
                }
            }
        }
        return (boolean) !$this->hasErrors();
    }
    
    public function getNameAttribute()
    {
        $form_name = $this->getParentFormName();
        if (!is_null($this->_formname)) {
            return $form_name . '[' . $this->getName() . ']';
        }
        return $this->getName();
    }
}
