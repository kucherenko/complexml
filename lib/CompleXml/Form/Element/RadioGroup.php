<?php

/**
 * Class for group of radio elements of form
 *
 * @author Andrey Kucherenko
 */
class CompleXml_Form_Element_RadioGroup extends CompleXml_Form_Element_Abstract
{

    protected $_radios = array();
    
    public function addRadio(CompleXml_Form_Element_Radio $radio)
    {
        $this->_radios[] = $radio;
        return $this;
    }
    
    public function setRadioElements($radio_elements)
    {
        $c = (array) $radio_elements;
        foreach ($c as $el) {
            $this->addRadio($el);
        }
        return $this;
    }
    
    public function flushRadioElements()
    {
        $this->_radios = array();
    }
    
    public function getRadioElements()
    {
        return (array) $this->_radios;
    }
    
    public function setValue($value)
    {
        parent::setValue($value);
        $this->setCheckedRadio();
        return $this;
    }
    
    public function render(CompleXml_View $view)
    {
        $this->setCheckedRadio();
        $this->addAttribute('type', $this->_type);
        $this->addAttribute('name', $this->getNameAttribute());
        $this->addAttribute('value', $this->getValue());
        $attributes = $this->getAttributes();
        foreach ($attributes as $name => $value) {
            $view->writeAttribute($name, $value);
        }
        $ratio_elements = $this->getRadioElements();
        foreach ($ratio_elements as $el) {
            $el->setName($this->getNameAttribute());
            $el->render($view);
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
    
    protected function setCheckedRadio()
    {
        $value = $this->getValue();
        $ratio_elements = $this->getRadioElements();
        foreach ($ratio_elements as $el) {
            if ($el->getValue() == $value) {
                $el->setChecked(true);
            } else {
                $el->setChecked(false);
            }
        }
    }
}

