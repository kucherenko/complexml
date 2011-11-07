<?php

/**
 * Class for working with form
 *
 * @author Andrey Kucherenko
 */
class CompleXml_Form implements Countable, Iterator, CompleXml_Form_Interface
{
    
    protected $_elements = array();
    protected $_action = null;
    protected $_enctype = null;
    protected $_method = null;
    protected $_name = null;
    protected $_label = null;
    protected $_formname = null;
    protected $_errors = array();
    protected $_values;
    protected $_attributes = array();
    
    public function  __construct()
    {
        $this->init();
    }

    public function init()
    {

    }

    public function getErrors()
    {
        return $this->_errors;
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
    
    public function getParentFormName()
    {
        return $this->_formname;
    }
    
    public function setParentFormName($name)
    {
        $this->_formname = $name;
        return $this;
    }
    
    public function bind($values)
    {
        $this->flushValues();
        $this->setValues($values);
    }
    
    public function addElement(CompleXml_Form_Interface $element, $name = null)
    {
        if (!is_null($name)) {
            $element->setName($name);
        }
        $this->_elements[$element->getName()] = $element;
        return $this;
    }
    
    public function removeElement($name)
    {
        $this->__unset($name);
        return $this;
    }
    
    public function getElement($name)
    {
        if (isset($this->_elements[$name])) {
            return $this->_elements[$name];
        }
        return null;
    }
    
    public function isValid($source)
    {
        $this->setValues($source);
        foreach ($this->_elements as $name => $element) {
            $element_data = $element->getValue();
            try {
                $is_valid = (boolean) $this->{'validate' . $name}($element_data) && $element->isValid($element_data);
            } catch (CompleXml_Form_Exception $e) {
                continue;
            }
            if (!$is_valid) {
                $this->_errors[$name] = $element->getErrors();
            }
        }
        return (boolean) !count($this->_errors);
    }
    
    public function setAction($action)
    {
        $this->_action = $action;
        return $this;
    }
    
    public function getAction()
    {
        return $this->_action;
    }
    
    public function setEnctype($type)
    {
        $this->_enctype = $type;
        return $this;
    }
    
    public function getEnctype()
    {
        return $this->_enctype;
    }
    
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function setLabel($label)
    {
        $this->_label = $label;
        return $this;
    }
    
    public function getLabel()
    {
        return $this->_label;
    }
    
    public function setMethod($method)
    {
        $method = strtolower($method);
        if (($method != 'post') && ($method != 'get')) {
            throw new CompleXml_Form_Exception('Method not suported');
        }
        $this->_method = $method;
        return $this;
    }
    
    public function getMethod()
    {
        return $this->_method;
    }
    
    public function render(CompleXml_View $view)
    {
        if (is_null($this->getParentFormName())) {
            $view->startElement('form');
        }

        if (!is_null($this->getMethod())) {
            $this->addAttribute('method', $this->getMethod());
        }

        if (!is_null($this->getAction())) {
            $this->addAttribute('action', $this->getAction());
        }

        if (!is_null($this->getEnctype())) {
            $this->addAttribute('enctype', $this->getEnctype());
        }

        if (!is_null($this->getName())) {
            $this->addAttribute('name', $this->getName());
        }

        $attributes = $this->getAttributes();
        foreach ($attributes as $name => $value) {
            $view->writeAttribute($name, $value);
        }
        if (is_null($this->getParentFormName())) {
            $view->startElement('fieldset');
            if (!is_null($this->getLabel())) {
                $view->writeElement('legend', $this->getLabel());
            }

        }
        foreach ($this->_elements as $element) {
            if (!($element instanceof CompleXml_Form_Element_Hidden)) {
                $view->startElement('fieldset');
            }
            $element->setParentFormName($this->_name);
            $element->render($view);
            if (!is_null($element->getLabel())) {
                $view->writeElement('legend', $element->getLabel());
            }
            if (!($element instanceof CompleXml_Form_Element_Hidden)) {
                $view->endElement();
            }
        }
        if (is_null($this->getParentFormName())) {
            $view->endElement();
            $view->endElement();
        }

        return $view;
    }
    
    public function flushValues()
    {
        $this->_values = array();
        foreach ($this->_elements as $name => $value) {
            $value->setValue(null);
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setValue($name, $value)
    {
        $this->_values[$name] = $value;

        if (isset($this->_elements[$name])) {
            $this->_elements[$name]->setValues(array($name=>$value));
        } else {
            throw new CompleXml_Form_Exception('Element ' . $name . ' not found in form.');
        }
    }
    
    public function getValue($name)
    {
        if (isset($this->_values[$name])) {
            return $this->_values[$name];
        }
        return null;
    }
    
    public function setValues($values)
    {
        $this->_values = (array) $values;
        foreach ($this->_values as $key => $value) {
            try {
                $this->setValue($key, $value);
            } catch (Exception $e) {
                continue;
            }
        }
    }
    
    public function getValues()
    {
        return $this->_values;
    }

    /**
     * Magic method for get object field
     */
    public function __get($name)
    {
        return $this->getElement($name);
    }

    /**
     * magic method for set object field
     */
    public function __set($name, $value)
    {
        $this->addElement($value, $name);
    }

    /**
     * Magic method for check isset object field
     */
    public function __isset($name)
    {
        return isset($this->_elements[$name]);
    }

    /**
     * Magic method for unset object field
     */
    public function __unset($name)
    {
        unset($this->_elements[$name]);
    }

    /**
     * Defined by Iterator interface
     *
     * @return boolean
     */
    public function valid()
    {
        return $this->current() !== false;
    }

    /**
     * Defined by Iterator interface
     *
     */
    public function next()
    {
        next($this->_elements);
    }

    /**
     * Defined by Iterator interface
     *
     */
    public function rewind()
    {
        reset($this->_elements);
    }

    /**
     * Defined by Countable interface
     *
     * @return int
     */
    public function count()
    {
        return count($this->_elements);
    }

    /**
     * Defined by Iterator interface
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->_elements);
    }

    /**
     * Defined by Iterator interface
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->_elements);
    }
    
    public function  __call($name, $arguments)
    {
        if (strpos($name, 'validate') !== 0) {
            throw new CompleXml_Form_Exception('Called undefined validator method');
        }
        $element_name = strtolower(substr($name, 8));
        $element = $this->getElement($element_name);
        if (is_null($element)) {
            throw new CompleXml_Form_Exception('Called undefined element validator');
        }
        return $element->isValid($arguments[0]);
    }
}
