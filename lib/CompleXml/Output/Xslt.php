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
 * Класс преобразовывающий XML в HTML при помощи XSLT,
 * и передающий результат в браузер
 */
class CompleXml_Output_Xslt implements CompleXml_Output_Interface
{

    public static function execute(CompleXml_Controller $ControllerObject)
    {
        $settings = CompleXml_Config::readComponentSettings(__CLASS__);
        $ControllerObject->View->setLocalePath($settings['locales']);
        $file = $settings['templates'].DIRECTORY_SEPARATOR.$ControllerObject->View->getTemplate().'.xsl';
        if (!file_exists($file)){
            throw new CompleXml_Output_Exception('Templates file '.$file.' not found');
        }
        $ControllerObject->View->setLocalePath($settings['locales']);
        $xmlString = $ControllerObject->View->getXml ();

        $xml = new DomDocument ( );
        $xml->loadXML ( $xmlString );
        @$xml->xinclude ();

        if (extension_loaded('xslcache')){
            $xslt = new xsltCache;
            $xslt->importStyleSheet($file);
            $newDom = $xslt->transformToDoc($xml);
        }else{
            $xsl = new DomDocument ( );
            $xsl->load ( $file );

            $proc = new XsltProcessor ( );
            $xsl = $proc->importStylesheet ( $xsl );

            $newDom = $proc->transformToDoc ( $xml );
        }
        $str = $newDom->saveHTML ();
        $ControllerObject->Response->nocache ();
        echo $str;
        return true;
    }
}