<?php

/**
 * Class for Multiple element of form
 *
 * @author Andrey Kucherenko
 */
class CompleXml_Form_Element_Multiple extends CompleXml_Form_Element_Select
{
    
    public function init()
    {
        $this->addAttribute('multiple', 'multiple');
    }
}

