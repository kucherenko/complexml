<?php
/**
 * CompleXml Framework
 *
 * @category   CompleXml
 * @package    CompleXml_Db
 * @copyright  Copyright (c) 2008 Andrey Kucherenko
 * @license    New BSD License
 * @version    0.3
 */

/**
 * Класс для подключения к базе данных. 
 * Db connector. 
 *
 */
class CompleXml_Db_Connection extends PDO
{
    /**
     * Строка параметров для подключения. 
     * Params string. 
     *
     * @var string
     */
    private $_dsn = null;
    private $_username = null;
    private $_password = null;
    /**
     * Параметры соединения. 
     * DB options. 
     *
     * @var array
     */
    private $_options = null;
    /**
     * Тип перебора записей. 
     * Mode of fetch query result
     *
     * @var string
     */
    private $_fetchMode = PDO::FETCH_ASSOC;
    /**
     * Конструктор класса, получает параметры соединения. 
     * Class constructur, get prepared config. 
     * 
     * @param array $config
     */
    function __construct ($dsn, $username=null, $password = null, $options = null)
    {
        $start_time = microtime(true);
        $this->_dsn = $dsn;
        $this->_username = $username;
        $this->_password = $password;
        $this->_options = $options;
        try {
            parent::__construct($this->_dsn, $this->_username, $this->_password, $this->_options);
        } catch (PDOException $e) {
            CompleXml_Log::add($e->getMessage(), CompleXml_Log::ERR);
            throw new CompleXml_Db_Exception($e->getMessage());
        }
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection_time = microtime(true) - $start_time;
        CompleXml_Log::add('Connection created, connection time - '.$connection_time, CompleXml_Log::DEBUG);
    }
    /**
     * Устанавливает метод перебора результатов запроса. 
     * Set fetch mode for query results. 
     *
     * @param integer $param
     */
    public function setFetchMode ($param)
    {
        $this->fetchMode = $param;
    }
    /**
     * Return fetch mode for query results.
     *
     * @param integer $param
     */
    public function getFetchMode ()
    {
        return $this->fetchMode;
    }
    /**
     * Выполняет запрос к базе данных без возвращения множества записей, 
     * возвращает колличество затронутых полей. 
     * Execute a query that does not return a row set, returning the number of affected row. 
     *
     * @param string $sql
     * @return int
     */
    public function exec ($sql)
    {
        $start_time = microtime(true);
        try {
            $res = parent::exec($sql);
        } catch (PDOException $e) {
            CompleXml_Log::add($e->getMessage() . '. Error at query: ' . $sql, CompleXml_Log::ERR);
            #require_once 'CompleXml/Db/Exception.php';
            throw new CompleXml_Db_Exception('SQL: ' . $sql . " " . $e->getMessage());
        }
        $exec_time = microtime(true) - $start_time;
        CompleXml_Log::add('Query '.$sql.' executed, execute time - '.$exec_time, CompleXml_Log::DEBUG);
        return $res;
    }
    /**
     * Подготавливает и выполняет запрос, возвращает объект содержащий результаты запроса. 
     * Prepare and execute $sql; returns the statement object for iteration. 
     *
     * @param string $sql
     * @param array $arg
     * @return PDOStatement 
     */
    public function query ($sql, $arg = null)
    {
        $start_time = microtime(true);
        try {
            $result = parent::query($sql);
        } catch (PDOException $e) {
            CompleXml_Log::add($e->getMessage() . '. Error at query: ' . $sql, CompleXml_Log::ERR);
            throw new CompleXml_Db_Exception('SQL: ' . $sql . " " . $e->getMessage());
        }
        $result->setFetchMode($this->_fetchMode);
        $exec_time = microtime(true) - $start_time;
        CompleXml_Log::add('Query '.$sql.' executed, execute time - '.$exec_time, CompleXml_Log::DEBUG);
        return $result;
    }
    /**
     * "Экранирует" строку для запроса. 
     * Quote query string. 
     *
     * @param string $param
     * @return string
     */
    public function quote ($param)
    {
        if (is_array($param)) {
            return $this->quoteArray($param);
        } else {
            return parent::quote($param);
        }
    }
    /**
     * "Экранирует" массив строк
     * Quote array of strings
     * 
     * @param array $array
     * @return array
     */
    private function quoteArray ($array)
    {
        if (! empty($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = $this->quote($value);
            }
        }
        return $array;
    }
}