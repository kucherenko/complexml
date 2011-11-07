<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Listener
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * 
 * Абстрактный класс, общий для всех типов Listener.
 * Слушатели событий запускаются до и после выполнения методов в объекте.
 */
abstract class CompleXml_Listener_Abstract
{
    protected $_object;

    /**
     * Class constructor.
     *
     * @deprecated not needed in abstract listeber class.
     * 
     * @param object $Object
     */
    public function  __construct($Object = null) {
        if (is_object($Object)){
            $this->_object = $Object;
        }        
    }
    
    abstract function beforeAction($method, $object = null);
    abstract function afterAction($method, $object = null);
}
