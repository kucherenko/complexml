<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Cookies
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * Класс, работающий с множеством данных cookie
 */
class CompleXml_Dataset_Cookies extends CompleXml_Dataset
{
    public function __construct ()
    {
        parent::__construct(@$_COOKIE);
    }
    
    public function setValue($name, $value, $expires = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        setcookie($name, $value, $expires, $path, $domain, $secure, $httponly); 
        parent::setValue($name, $value);
            
    }

    public function __unset ($name)
    {
        setcookie($name, null, time()-1);
        parent::__unset($name);
    }
}
