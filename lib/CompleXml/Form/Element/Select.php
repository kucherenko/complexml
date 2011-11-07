<?php

/**
 * Class for Text element of form
 *
 * @author Andrey Kucherenko
 */
class CompleXml_Form_Element_Select extends CompleXml_Form_Element_Abstract
{

    protected $_options = array();
    
    public function addOption($name, $value)
    {
        $this->_options[$name] = $value;
        return $this;
    }
    
    public function setOptions($array)
    {
        $array = (array) $array;
        foreach ($array as $key=>$value) {
            $this->addOption($key, $value);
        }
        return $this;
    }
    
    public function flushOptions()
    {
        $this->_options = array();
        return $this;
    }
    
    public function render(CompleXml_View $view)
    {
        $view->startElement('select');
        $this->addAttribute('name', $this->getNameAttribute());
        $attributes = $this->getAttributes();
        foreach ($attributes as $name => $value) {
            $view->writeAttribute($name, $value);
        }
        $values = (array) $this->getValue();
        foreach ($this->_options as $key => $value) {
            $view->startElement('option');
            $view->writeAttribute('value', $key);
            if (in_array($key, $values)) {
                $view->writeAttribute('selected', 'selected');
            }
            $view->writeRawXml($value);
            $view->endElement();
        }
        $view->endElement();
        $errors = $this->getErrors();
        if (!empty($errors)) {
            $view->startElement('ul');
            foreach ($errors as $key => $value) {
                $view->writeElement('li', $value);
            }
            $view->endElement();
        }
    }
}

