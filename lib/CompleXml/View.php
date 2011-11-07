<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_View
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

//require_once 'CompleXml/View/XmlWriter.php';
/**
 * Класс, для работы с данными предназначеными для выведения пользователю
 */
class CompleXml_View extends CompleXml_View_XmlWriter
{
    const MESSAGE_ERROR = 'error';

    const MESSAGE_INFO = 'info';

    const MESSAGE_WARNING = 'warning';

    const MESSAGE_DONE = 'done';

    const MESSAGE_DEBUG = 'debug';

    protected static $_messages = array(

    );

    private $_is_init = false;

    private $_locale;

    private $_locale_path;

    private $_language;

    private $_territory;

    private $_settings = array();
    /**
     * Path to template
     * @var string
     */
    private $_template = null;
    /**
     * @var CompleXml_View
     */
    static private $instance = null;

    public static function getInstance()
    {
        if (is_null(self::$instance)){
            self::$instance = new CompleXml_View();
            $settings = CompleXml_Config::readComponentSettings(__CLASS__);
            if (isset ($settings['attributes'])){
                self::$instance->_settings = $settings['attributes'];
            }
            self::$instance->init($settings['root'], $settings['content_node']);
        }
        return self::$instance;
    }

    public function init($document, $content = null)
    {
        $this->startElement($document);
        if (!is_null($content)){
            $this->startElement($content);
        }
        $this->_is_init = true;
    }

    public function getXml()
    {
        if (count (self::$_messages)){
            $this->writeElement('messages', self::$_messages);
        }
        $this->endElement();

        if (!empty ($this->_language)){
            $this->startElement('locale');
            $this->writeAttribute('language', $this->_language);
            $this->writeAttribute('territory', $this->_territory);
            $this->writeElement('path', $this->getLocalePath());
            $this->writeElement('name', $this->_language.'_'.$this->_territory);
            $this->endElement();
        }

        if (!empty ($this->_settings)){
            $this->assign('settings', $this->_settings);
        }

        $this->endElement();
        return $this->outputMemory();
    }

    public function setAttribute($name, $value)
    {
        $this->_settings[$name] = $value;
    }

    public function getAttribute($name)
    {
        if (isset($this->_settings[$name])){
            return $this->_settings[$name];
        }else{
            return null;
        }
    }

    public function getMessages()
    {
        return self::$_messages;
    }

    public function addMessage($message, $type, $replacement = null)
    {
        $rep_result = array();
        if (!is_null($replacement)){
            $replacement = (array) $replacement;
            foreach ($replacement as $key=>$value){
                $rep_result[] = array('find'=>$key, 'replace'=>$value);
            }
        }

        self::$_messages[] = array(
                'type' => $type , 'message' => $message, 'replacement' => $rep_result
        );
    }

    public function setMessages(array $messages)
    {
        self::$_messages = $messages;
    }

    public function addError ($message, $replacement = null)
    {
        $this->addMessage($message, CompleXml_View::MESSAGE_ERROR, $replacement);
    }

    public function addInfo ($message, $replacement = null)
    {
        $this->addMessage($message, CompleXml_View::MESSAGE_INFO,  $replacement);
    }

    /**
     * @return string
     */
    public function getLocale ()
    {
        return $this->_locale;
    }
    /**
     * @param string $locale
     */
    public function setLocale ($locale)
    {
        list ($this->_language, $this->_territory) = explode('_', $locale);
        $this->_locale = $locale;
    }
    /**
     * @return string
     */
    public function getLocalePath ()
    {
        return $this->_locale_path;
    }
    /**
     * @param string $path
     */
    public function setLocalePath ($path)
    {
        $this->_locale_path = $path;
    }

    public function getLanguage()
    {
        return $this->_language;
    }

    public function getTerritory()
    {
        return $this->_territory;
    }
    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->_template = $template;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    public function assign($node, $value= null)
    {
        if (!is_null($value)){
            $this->writeElement($node, $value);
            return;
        }
        $trace = debug_backtrace();
        $overbearing = $trace[1];

        if(isset ($overbearing['class'])&&isset ($overbearing['function'])){
            $controller = str_replace('controller', '', strtolower($overbearing['class']));
            $action = str_replace('action', '', strtolower($overbearing['function']));
            $this->writeElement($controller.'-'.$action, $node);
        }
    }
}
