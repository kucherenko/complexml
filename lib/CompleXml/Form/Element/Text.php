<?php

/**
 * Class for Text element of form
 *
 * @author Andrey Kucherenko
 */
class CompleXml_Form_Element_Text extends CompleXml_Form_Element_Abstract
{

    protected $_type = 'text';
    
    public function render(CompleXml_View $view)
    {
        $this->addAttribute('type', $this->_type);
        $this->addAttribute('name', $this->getNameAttribute());
        $this->addAttribute('value', $this->getValue());
        
        $view->startElement('input');
        $attributes = $this->getAttributes();
        foreach ($attributes as $name => $value) {
            $view->writeAttribute($name, $value);
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

