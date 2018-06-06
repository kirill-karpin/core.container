<?php


namespace Toolbox\Core\Util;


trait Lang
{
    public static function translit($str, $lang = 'ru', $params = [
        "replace_space" => "_",
        "replace_other" => "_"
    ])
    {
        $str = trim($str);

        return \CUtil::translit($str, $lang, $params);
    }

    public static function toCamelCase($str, $capitalise_first_char = false){

        if($capitalise_first_char) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }


    function toUnderscore($str, $upper = false) {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        if ($upper) {
            return strtoupper(preg_replace_callback('/([A-Z])/', $func, $str)) ;
        }
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

    public static function fromCamelCase($str, $upper = false) {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        if ($upper) {
            return strtoupper(preg_replace_callback('/([A-Z])/', $func, $str)) ;
        }
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }
}