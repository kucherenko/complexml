<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Dataset
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * Класс, работающий с множеством данных сессии пользователя
 */
class CompleXml_Dataset_Session extends CompleXml_Dataset
{
    public function __construct ()
    {
        @session_start();
        parent::__construct($_SESSION);
    }
    /*
     * Установка переменной в сессию
     */
    public function setValue ($name, $val)
    {
        $_SESSION[ $name ] = $val;
        parent::setValue($name, $val);
    }
    /**
     * Уничтожение сессии
     */
    public function destroy ()
    {
        session_destroy();
    }
    
    /**
     * Удаление переменной из сессии
     */
    public function unregister ($name)
    {
        if (isset($_SESSION[$name])) {
            parent::__unset($name);
            unset($_SESSION[ $name ]);
        }
    }

    public function __unset ($name)
    {
        $this->unregister($name);
        parent::__unset($name);

    }
}
