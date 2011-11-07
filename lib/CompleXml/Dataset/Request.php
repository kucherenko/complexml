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
 * Класс, работающий с множеством данных запроса пользователя
 */
class CompleXml_Dataset_Request extends CompleXml_Dataset
{
    /**
     *
     * @var CompleXml_Dataset
     */
    public $Post;
    /**
     *
     * @var CompleXml_Dataset
     */
    public $Get;
    /**
     *
     * @var CompleXml_Dataset
     */
    public $Uri;

    public function __construct()
    {
        parent::__construct($_REQUEST);
        $this->Post = new CompleXml_Dataset($_POST);
        $this->Get = new CompleXml_Dataset($_GET);

        $source = array();
        if (isset($_SERVER['REQUEST_URI'])) {
            $url_array = (array) explode('?', $_SERVER['REQUEST_URI']);
            $source = explode('/', $url_array[0]);
            foreach ($source as $key => $value) {
                if (empty($value)) {
                    unset($source[$key]);
                }
            }
        }

        $this->Uri = new CompleXml_Dataset($source);
    }

    public function isPost()
    {
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            return false;
        }
        return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';
    }

    public function isGet()
    {
        if (!isset($_SERVER['REQUEST_METHOD'])) {
            return false;
        }
        return strtoupper($_SERVER['REQUEST_METHOD']) == 'GET';
    }

    public function isAjax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }
}
