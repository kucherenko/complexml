<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Output
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */
/**
 * Класс передающий XML в браузер без преобразования
 */
class CompleXml_Output_Xml implements CompleXml_Output_Interface {

    public static function execute(CompleXml_Controller $ControllerObject) {
        $settings = CompleXml_Config::readComponentSettings(__CLASS__);
        $only_xml = (boolean) $settings['only_xml'];
        if ((!$ControllerObject->Request->isAjax()) && !$only_xml) {
            return false;
        }
        $ControllerObject->View->setLocalePath(CompleXml_Config::getSettings('Output_Browser', 'locales'));
        $xmlString = $ControllerObject->View->getXml();
        if ($ControllerObject->View->hasIncludedXml()) {
            $xml = new DomDocument ( );
            $xml->loadXML ( $xmlString );
            @$xml->xinclude ();
            $outputXml = $xml->saveXML ();
        }else{
            $outputXml = $xmlString;
        }
        header ( "Content-type: application/xml" );
        echo $outputXml;
        return true;
    }
}