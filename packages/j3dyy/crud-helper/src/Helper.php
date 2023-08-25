<?php

namespace J3dyy\CrudHelper;

/**
 * @author jedy
 * @since 0.1
 */
class Helper
{

    /**
     * @param $link
     * @param array $params
     * @return string
     * @description searching {wildcards} into string and replacing it given $params
     */
    public static function parseLink($link, array $params = []): string{
        $matches = [];
        $matcher = "/\{(.*?)\}/";
        preg_match_all($matcher, $link, $matches);

        if (isset($matches[1])){
            foreach ($matches[1] as $k => $match){
                if (isset($params[$match])){
                   $link =  str_replace($matches[0][$k], $params[$match],$link);
                }
            }
        }
        return $link;
    }
}
