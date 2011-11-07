<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Router
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */
/**
 * Базовый класс маршрутизаторов
 */
class CompleXml_Router
{
    /**
     * CompleXml_Dataset_Request object
     * @var CompleXml_Dataset_Request
     */
    public $Request;
    /**
     * CompleXml_Response object
     * @var CompleXml_Response
     */
    public $Response;
    /**
     * Router settings
     * @var array
     */
    protected $_settings;

    public function __construct ()
    {
        $this->Request = new CompleXml_Dataset_Request();
        $this->Response = new CompleXml_Response();
    }

    public function init()
    {}

    /**
     * @uri /
     * @uri /<controller:w>
     * @uri /<controller:w>/<action:w>
     */
    public function defaultRoute($controller, $action)
    {
        if (is_null($controller)){
            $controller = 'index';
        }
        if (is_null($action)){
            $action = 'index';
        }
        return array('controller'=>$controller, 'action'=>$action);
    }
}