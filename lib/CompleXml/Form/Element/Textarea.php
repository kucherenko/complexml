<?php

/**
 * Class for Textarea element of form
 *
 * @author Andrey Kucherenko
 */
class CompleXml_Form_Element_Textarea extends CompleXml_Form_Element_Abstract
{

    public function render(CompleXml_View $view)
    {
        $view->startElement('textarea');
        $this->addAttribute('name', $this->getNameAttribute());
        $attributes = $this->getAttributes();
        foreach ($attributes as $name => $value) {
            $view->writeAttribute($name, $value);
        }
        $view->writeRawXml($this->getValue());
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

