<?php

/**
 * Класс, позволяющий получать информацию об объявленных классах
 *
 * @author Andrey Kucherenko <kucherenko.andrey@gmail.com>
 */
class CompleXml_Reflection_Class extends ReflectionClass
{

    /**
     * Имя класса
     */
    private $_name;
    private $_annotations;

    public function __construct($name)
    {
        $this->_name = $name;
        parent::__construct($name);
        $this->_annotations = CompleXml_Reflection_Annotations::parseDoc($this->getDocComment());
    }

    /**
     * Возращает 1-й метод класса, который удовлетворяет условиям $params и $filter
     * @param array $params
     * @param int $filter
     * @return CompleXml_Reflection_Method
     */
    public function getMethodByAnnotation($params, $filter = ReflectionMethod::IS_PUBLIC)
    {
        $methods = $this->getMethods($filter);

        foreach ($methods as $method) {
            $method = new CompleXml_Reflection_Method($this->_name, $method->getName());
            $annotations = CompleXml_Reflection_Annotations::parseDoc($method->getDocComment());
            $is_valid = false;
            foreach ($params as $name => $param) {
                if (isset($annotations[$name])) {
                    if (!CompleXml_Reflection_Annotations::checkAnnotation($annotations[$name], $param)) {
                        continue 2;
                    } else {
                        $is_valid = true;
                    }
                }
            }
            if ($is_valid) {
                return $method;
            }
        }
    }

    /**
     * Возращает методы класса, которые удовлетворяют условиям $params и $filter
     * @param array $params
     * @param int $filter
     * @return CompleXml_Reflection_Method
     */
    public function getMethodsByAnnotation($params, $filter = ReflectionMethod::IS_PUBLIC)
    {
        $methods = $this->getMethods($filter);
        $result = array();
        foreach ($methods as $method) {
            $method = new CompleXml_Reflection_Method($this->_name, $method->getName());
            $annotations = CompleXml_Reflection_Annotations::parseDoc($method->getDocComment());

            $is_valid = false;
            foreach ($params as $name => $param) {
                if (isset($annotations[$name])) {
                    if (!CompleXml_Reflection_Annotations::checkAnnotation($annotations[$name], $param)) {
                        continue 2;
                    } else {
                        $is_valid = true;
                    }
                }
            }
            if ($is_valid) {
                $result[] = $method;
            }
        }
        return $result;
    }

    public function getCXMethods($filter)
    {
        $methods = parent::getMethods($filter);
        $result = array();
        foreach ($methods as $method) {
            $result[] = new CompleXml_Reflection_Method($this->_name, $method->getName());
        }
        return $result;
    }

    public function getAnnotation($name)
    {
        return @$this->_annotations[$name];
    }

    public function getAnnotations()
    {
        return $this->_annotations;
    }

    public function getDescription()
    {
        $doc = $this->getDocComment();
        $doc = str_replace('*', '', $doc);
        $docArray = explode(PHP_EOL, $doc);
        $result = '';
        foreach ($docArray as $line) {
            $line = trim($line);
            if (isset($line[0]) && ($line[0] != '@') && ($line[0] != '/')) {
                $result.=$line . PHP_EOL;
            }
        }
        return $result;
    }

}
