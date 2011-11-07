<?php
/**
 * CompleXml_Reflection_Workspace класс для получения информации о текущем
 * состоянии рабочей области на предмет объявленых и загруженых классов.
 *
 * @author Andrey Kucherenko <kucherenko.andrey@gmail.com>
 */
class CompleXml_Reflection_Workspace {

    private $_classes;

    private static $_class_cache = array();

    public function __construct()
    {
        $this->reloadClasses();
    }

    public function reloadClasses()
    {
        self::$_class_cache = array();
        $this->_classes = get_declared_classes();
    }
    /**
     * Метод проверяет, объявлен ли класс
     * @param string $class
     * @return boolean
     */
    public function isClassDeclared($class)
    {
        return in_array($class, $this->_classes);
    }
    /**
     * Метод, который возвращает 1-й объявленный класс, который наследуется от $parent и
     * удовлетворяет параметрам $params
     * @param string $parent
     * @param array $params
     * @return CompleXml_Reflection_Class
     */
    public function getClass($parent, $params = null)
    {
        $md5_hash = md5($parent.serialize($params));
        if (isset(self::$_class_cache[$md5_hash])){
            return self::$_class_cache[$md5_hash];
        }
        $parent_class = new CompleXml_Reflection_Class($parent);
        foreach ($this->_classes as $class)
        {
            $ref = new CompleXml_Reflection_Class($class);
            if ($ref->isSubclassOf($parent)){
                if (empty($params)){
                    self::$_class_cache[$md5_hash] = new CompleXml_Reflection_Class($class);
                    return self::$_class_cache[$md5_hash];
                }else{
                    $is_valid = false;
                    foreach ($params as $name=>$param){
                        if (!is_null($ref->getAnnotation($name))){
                            if (!CompleXml_Reflection_Annotations::checkAnnotation($ref->getAnnotation($name), $param)){
                                continue 2;
                            }else{
                                $is_valid = true;
                            }
                        }
                    }
                    if ($is_valid){
                        self::$_class_cache[$md5_hash] = new CompleXml_Reflection_Class($class);
                        return self::$_class_cache[$md5_hash];
                    }
                }
            }
        }
    }
    
    /**
     * Метод, который возвращает массив классов, которые наследуется от $parent и
     * удовлетворяют параметрам $params
     * @param string $parent
     * @param array $params
     * @return CompleXml_Reflection_Class
     */
    public function getClasses($parent, $params = null)
    {
        $result = array();
        $parent_class = new CompleXml_Reflection_Class($parent);
        foreach ($this->_classes as $class){
            $ref = new CompleXml_Reflection_Class($class);
            if ($ref->isSubclassOf($parent)){
                if (empty($params)){
                    $result[] = new CompleXml_Reflection_Class($class);
                }else{
                    $is_valid = false;
                    foreach ($params as $name=>$param){
                        if (!is_null($ref->getAnnotation($name))){
                            if (!CompleXml_Reflection_Annotations::checkAnnotation($ref->getAnnotation($name), $param)){
                                continue 2;
                            }else{
                                $is_valid = true;
                            }
                        }
                    }
                    if ($is_valid){
                        $result[] = new CompleXml_Reflection_Class($class);
                    }
                }
            }
        }
        return $result;
    }
    /**
     * Метод возвращает список всех объявленных классов
     * @return array
     */
    public function getAllClasses()
    {
        return $this->_classes;
    }
}
