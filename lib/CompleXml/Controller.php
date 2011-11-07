<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Controller
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * Main parent controller class
 */
class CompleXml_Controller
{
    /**
     * CompleXml_Dataset_Request object
     *
     * @var CompleXml_Dataset_Request
     */
    public $Request;
    /**
     * CompleXml_Dataset_Session object
     *
     * @var CompleXml_Dataset_Session
     */
    public $Session;
    /**
     * CompleXml_Dataset_Cookies object
     *
     * @var CompleXml_Dataset_Cookies
     */
    public $Cookies;
    /**
     * CompleXml_Router_Response object
     *
     * @var CompleXml_Router_Response
     */
    public $Response;
    /**
     * CompleXml_View object
     *
     * @var CompleXml_View
     */
    public $View;
    /**
     *
     * @var CompleXml_Router
     */
    public $Router;

    private $_session_name = '__CompleXml__Messages__';

    protected $_settings;

    public function __construct (CompleXml_Router $Router)
    {
        $this->Router = $Router;
        $this->Request = $Router->Request;
        $this->Response = $Router->Response;
        $this->Session = new CompleXml_Dataset_Session();
        $this->Cookies = new CompleXml_Dataset_Cookies();
        $this->View = CompleXml_View::getInstance();
        $messages = $this->Session->getValue($this->_session_name);
        if (!is_null($messages)){
            $this->View->setMessages($messages);
            $this->Session->unregister($this->_session_name);
        }
    }

    public function init(){}

    protected function _redirect ($url = null)
    {
        $messages = $this->View->getMessages();
        $lang = $this->View->getLanguage();
        if (!is_null($lang)&&!is_null($url)&&strpos($url,'/'.$lang)!==0){
           $url = '/'.$lang.$url;
        }
        if (!empty($messages)){
            $this->Session->setValue($this->_session_name, $messages);
        }
        if (!is_null($url)){
            $this->Response->location($url);
        }else {
            $this->Response->back();
        }
    }

    protected function _call($action, $controller = null)
    {
        if (is_null($controller)){
            $ref = new ReflectionClass($this);
            $controller = $ref->getName();
        }
        $this->Request->setValue('controller', $controller);
        $this->Request->setValue('action', $action);
        CompleXml_Application::runController($this->Router);
    }

    public function getMessagesSessionName()
    {
        return $this->_session_name;
    }
}