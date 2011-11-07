<?php
#require_once 'CompleXml/Utils/PlaceParser.php';
/**
 * CompleXml_Reflection_Annotations class
 *
 * @author Andrey Kucherenko (kucherenko.andrey@gmail.com)
 */
class CompleXml_Reflection_Annotations {

    private static $_cached_docs = array();

    public static function parseDoc($doc)
    {
        $md5_hash = md5($doc);
        if (isset(self::$_cached_docs[$md5_hash])){
            return self::$_cached_docs[$md5_hash];
        }
        $result = array();
        $doc = str_replace('*', '', $doc);
        $docArray = explode("\n", $doc);

        foreach ($docArray as $line) {
            $line = trim($line);
            if (isset($line[0])&&($line[0]=='@')){
                $row = explode(' ',  substr($line, 1), 2);
                if (isset($result[$row[0]]) && !is_array($result[$row[0]])){
                    $res = $result[$row[0]];
                    $result[$row[0]] = array();
                    $result[$row[0]][] = $res;
                    $result[$row[0]][] = @trim($row[1]);
                }elseif(isset($result[$row[0]])&&is_array($result[$row[0]])){
                    $result[$row[0]][] = @trim($row[1]);
                }else{
                    $result[$row[0]] = @trim($row[1]);
                }
            }
        }
        self::$_cached_docs[$md5_hash] = $result;
        return $result;
    }

    public static function checkAnnotation($annotation, $param)
    {
        if (!is_array($annotation)){
            return CompleXml_Utils_PlaceParser::isMatch($annotation, $param);
        }else{
            foreach ($annotation as $value) {
                if (CompleXml_Utils_PlaceParser::isMatch($value, $param)){
                    return true;
                }
            }
            return false;
        }
    }

    public static function getParametrs($annotations, $params)
    {
        $result = array();

        foreach ($annotations as $name => $annotation){
            if (!is_array($annotation)){
                if (isset($params[$name])){
                    $res = CompleXml_Utils_PlaceParser::getParams($annotation, $params[$name]);
                    if ($res!==false){
                        $result = array_merge($result, $res);
                    }
                }
            }else{
                foreach ($annotation as $value) {
                    if (isset($params[$name])){
                        $res = CompleXml_Utils_PlaceParser::getParams($value, $params[$name]);
                        if ($res!==false){
                            $result = array_merge($result, $res);
                        }
                    }
                }
            }
        }
        return $result;
    }
}

