<?php
/**
 * @author Andrey Kucherenko
 */

interface CompleXml_Form_Interface {

    public function isValid($value);

    public function setValues($values);

    public function getName();

    public function setName($name);

    public function getLabel();

    public function setLabel($label);

    public function getParentFormName();

    public function setParentFormName($label);

    public function render(CompleXml_View $view);

}
