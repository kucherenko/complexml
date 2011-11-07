<?php

/**
 * Class for Multiple checkbox element of form
 *
 * @author Andrey Kucherenko
 */
class CompleXml_Form_Element_MultipleCheckbox extends CompleXml_Form_Element_Abstract
{

    protected $_checkboxes = array();
    
    public function addCheckbox(CompleXml_Form_Element_Checkbox $checkbox)
    {
        $this->_checkboxes[] = $checkbox;
        return $this;
    }
    
    public function setCheckboxes($checkboxes)
    {
        $c = (array) $checkboxes;
        foreach ($c as $checkbox) {
            $this->addCheckbox($checkbox);
        }
        return $this;
    }
    
    public function flushCheckboxes()
    {
        $this->_checkboxes = array();
        return $this;
    }
    
    public function getCheckboxes()
    {
        return (array) $this->_checkboxes;
    }
    
    public function setValue($value)
    {
        parent::setValue($value);
        $this->setCheckedBoxes();
        return $this;
    }
    
    public function setValues($values)
    {
        $this->setValue($values);
        return $this;
    }
    
    public function render(CompleXml_View $view)
    {
        $this->setCheckedBoxes();
        $this->addAttribute('type', $this->_type);
        $this->addAttribute('name', $this->getNameAttribute());
        $this->addAttribute('value', $this->getValue());
        $attributes = $this->getAttributes();
        foreach ($attributes as $name => $value) {
            if (is_string($value)){
                $view->writeAttribute($name, $value);
            }           
        }
        foreach ($this->_checkboxes as $checkbox) {
            $checkbox->setParentFormName($this->getNameAttribute());
            $checkbox->setName($checkbox->getName());
            $checkbox->render($view);
        }

        $errors = $this->getErrors();
        if (!empty($errors)) {
            $view->startElement('ul');
            foreach ($errors as $key => $value) {
                $view->writeElement('li', $value);
            }
            $view->endElement();
        }
    }
    
    protected function setCheckedBoxes()
    {
        $values = (array) $this->getValue();
        
        $checkboxes = $this->getCheckboxes();
        foreach ($checkboxes as $el) {
            if (in_array($el->getValue(), $values)) {
                $el->setChecked(true);
            } else {
                $el->setChecked(false);
            }
        }
    }
}

