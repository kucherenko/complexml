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
 * Cлушатель событий, находит анотации к методам и выполняет их.
 */
class CompleXml_Listener_Behaviour extends CompleXml_Listener_Abstract
{

    /**
     * Метод, обрабатывающий аннотацию @before и вызывает соответствующий метод у объекта
     * @param string $method
     */
    public function beforeAction($method, $object = null)
    {
        if (is_null($object)) {
            $object = $this->_object;
        }
        $ref = new CompleXml_Reflection_Method(get_class($object), $method);
        $before = $ref->getAnnotation('before');
        if (!is_null($before)){
            if (method_exists($object, $before)){
                $object->$before();
            }else{
                throw new CompleXml_Listener_Exception_Behaviour('Method "' . $before . '" not exists in object ' . get_class($object));
            }
        }
    }
    /**
     * Метод, обрабатывающий аннотацию @after и вызывает соответствующий метод у объекта
     * @param string $method
     */
    public function afterAction($method, $object = null)
    {
        if (is_null($object)) {
            $object = $this->_object;
        }
        $ref = new CompleXml_Reflection_Method(get_class($object), $method);
        $after = $ref->getAnnotation('after');
        if (!is_null($after)){
            if (method_exists($object, $after)){
                $object->$after();
            }else{
                throw new CompleXml_Listener_Exception_Behaviour('Method '.$after.' not exists at object '. get_class($object));
            }
        }
    }
}