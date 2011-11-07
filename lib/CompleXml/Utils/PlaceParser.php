<?php

/**
 * PlaceParser - utils class for parse string like <teststring:w>.like.<qwerty:d>.complexml.org
 *
 * @author Andrey Kucherenko
 */
class CompleXml_Utils_PlaceParser {

    static public function getRegex($pattern) {
        $pattern = str_replace('?', '\?', $pattern);
        $patterns = array ('/<(\w+):([H|w|d]{1})>/');
        $replace = array ('(?<\1>\\\\\2+)');
        $pattern = preg_replace($patterns, $replace, $pattern);
        $pattern = str_replace('.', '\.', $pattern);
        if ($pattern[strlen($pattern)-1]!=')') {
            $pattern.= '$';
        }
        $pattern = '/' . str_replace('/', '\/', $pattern).'/i';
        return $pattern;
    }

    public static function isMatch($pattern, $string)
    {
        $pattern = self::getRegex($pattern);
        return (boolean) preg_match( $pattern, $string);
    }

    public static function getParams($pattern, $string)
    {
         $is_match = (boolean) preg_match(self::getRegex($pattern), $string, $matches);
         if (!$is_match){
             return false;
         }
         $result = array();
         foreach ($matches as $key=>$value){
            if (!is_numeric($key)){
                $result[$key] = $value;
            }
         }
         return $result;
    }

}
