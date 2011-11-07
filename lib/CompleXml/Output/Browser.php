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
#require_once 'CompleXml/Output/Interface.php';
/**
 * Класс передающий XML в браузер для XSLT преобразования
 */
class CompleXml_Output_Browser implements CompleXml_Output_Interface {

    public static function execute(CompleXml_Controller $ControllerObject) {
        if (!ini_get('browscap')) {
            return false;
        }
        $settings = CompleXml_Config::readComponentSettings(__CLASS__);
        $browsers = (array) $settings['xslt_browsers'];
        if (($settings['with_get_browsers_cache'])&&($ControllerObject->Session->getBool(__CLASS__))) {
            $browser_info = $ControllerObject->Session->getValue(__CLASS__);
        }else {
            $browser_info = get_browser();
            if ($settings['with_get_browsers_cache']) {
                $ControllerObject->Session->setValue(__CLASS__, $browser_info);
            }
        }
        $is_correct = false;
        foreach ($browsers as $name => $version) {

            if ((strpos($browser_info->browser,$name)===0)&&($browser_info->version>=$version)) {
                $is_correct = true;
                break;
            }
        }
        if (!$is_correct) {
            return false;
        }
        $ControllerObject->View->setLocalePath($settings['locales']);
        $file = $settings['templates'].'/'.$ControllerObject->View->getTemplate().'.xsl';

        $xmlString = $ControllerObject->View->getXml();

        if ($ControllerObject->View->hasIncludedXml()) {
            $xml = new DomDocument ( );
            $xml->loadXML ( $xmlString );
            @$xml->xinclude ();
            $outputXml = $xml->saveXML ();
        }else {
            $outputXml = $xmlString;
        }

        $outputXml = str_replace('?>', '?><?xml-stylesheet type="text/xsl" href="'.$file.'"?>', $outputXml);

        header ( "Content-type: text/xml" );
        $ControllerObject->Response->nocache ();
        echo $outputXml;
        return true;
    }
}