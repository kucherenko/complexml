<?php

/**
 * Class for radio element of form
 *
 * @author Andrey Kucherenko
 */
class CompleXml_Form_Element_Radio extends CompleXml_Form_Element_Text
{

    protected $_type = 'radio';

    public function setChecked($is_checked = true)
    {
        if ($is_checked) {
            $this->addAttribute('checked', 'checked');
        } else {
            $this->removeAttribute('checked');
        }
    }


    public function render(CompleXml_View $view)
    {
        $view->startElement('label');
        parent::render($view);
        $label = $this->getLabel();
        if (!empty($label)) {
            $view->writeRawXml($label);
        }
        $view->endElement();
    }
}

