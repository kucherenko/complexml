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
  * Класс ответа
  */
class CompleXml_Response
{
    public function location ($url)
    {
        header("Location: " . $url);
        die();
    }

    public function back ()
    {
        $url = getenv('HTTP_REFERER');
        if (! empty($url)) {
            $this->location($url);
        }
    }
    
    public function nocache ()
    {
        header("Expires: Mon, 19 Jul 2006 05:00:00 GMT"); // Date in past time
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
        header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1 header
        header("Cache-Control: post-check=0, pre-check=0", false); // no cache
        header("Pragma: no-cache");
    }
}