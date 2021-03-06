<?php

/**
 * Transform module
 *
 * @author Bálint Horváth <balint@snett.net>
 */

namespace Former;

class Transform {
    
    public static function Normalize($from) {
        $norma = array(
            'Š' => 'S', 'š' => 's', 'Ð' => 'D', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A',
            'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
            'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ő' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U',
            'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
            'å' => 'a', 'æ' => 'a', 'ă' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i',
            'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u',
            'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'ƒ' => 'f', ' ' => '-', 'ő' => 'o', 'ü' => 'u',
            'ę' => 'e', 'ś' => 's', 'ć' => 'c', 'č' => 'c', 'ť' => 't', 'ţ' => 't', 'ľ' => 'l',
            'Č' => 'c', 'ř' => 'r', 'ě' => 'e', '’' => '', '"' => '', '„' => '', '”' => '', '“' => '', '“' => '', '”'=>'',
            'ű' => 'u', '?' => '', ',' => '-', '.' => '', ':' => '', '!' => '', 'ż' => 'z', 'ł' => 'l', 'Ś' => 'S', 'ą' => 'a',
            'Ď' => 'D', '(' => '', ')' => '', 'ň' => 'n', "'" => '', '–' => '', '…' => '', 'ń' => '', 'ű' => 'u', 'ů' => 'u', 'Ľ' => 'L', 'Ł' => 'L', 'Ż'=>'Z', 'ŕ'=>'r', 'ď' => 'd',
            'Ň' => 'N', 'ĺ' => 'l', 'ț' => 't', 'ş' => 's'
        );
        $pronorma = array(
            '---' => '-', '--' => '-', '_' => '-', ' ' => '', '_-_' => '-', '_' => '-'
        );
        $normalized = strtolower(strtr(strtr($from, $norma), $pronorma));
        if ($normalized[strlen($normalized)-1] === '-'){
            $normalized = substr($normalized, 0, strlen($normalized)-1);
        }
        if ($normalized[0] === '-'){
            $normalized = substr($normalized, 1, strlen($normalized));
        }
        return $normalized;
    }
    
    public static function encode($object){
        return(md5($object));
    }

}
