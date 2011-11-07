<?php
/**
 * Класс, позволяющий получать информацию о методах классов
 *
 * @author Andrey Kucherenko <kucherenko.andrey@gmail.com>
 */
class CompleXml_Reflection_Method extends ReflectionMethod {

/**
 * Имя метода
 */
    private $_name;

    /**
     * Имя класса
     */
    private $_class;

    private $_annotations;

    public function __construct($class, $name) {
        $this->_class = $class;
        $this->_name = $name;
        parent::__construct($class, $name);
        $this->_annotations = CompleXml_Reflection_Annotations::parseDoc($this->getDocComment());
    }

    public function getAnnotation($name) {
        return @$this->_annotations[$name];
    }

    public function getAnnotations() {
        return $this->_annotations;
    }
    
    public function getDescription() {
        $doc = $this->getDocComment();
        $doc = str_replace('*', '', $doc);
        $docArray = explode(PHP_EOL, $doc);
        $result = '';
        foreach ($docArray as $line) {
            $line = trim($line);
            if (isset($line[0])&&($line[0]!='@')&&($line[0]!='/')) {
                $result.=$line.PHP_EOL;
            }
        }
        return $result;
    }
}
