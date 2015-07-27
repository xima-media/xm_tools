<?php
namespace TxXmTools\Classes\Helpers;

/**
 * Helferklasse für Typo3-Extensions mit Extbase
 * @version 1.0.0
 */
class Helper {

    /**
     * Modifies a string to remove all non ASCII characters and spaces.
     */
    static public function slugify($text) {
        
        $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'Ae', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'Oe', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'Ue', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'ae', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'oe', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', 'ü' => 'ue'
        );

        $text = strtr($text, $table);
        
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;

    }

    static public function slugify2($string) {
        // Remove accents from characters
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

        // Everything lowercase
        $string = strtolower($string);

        // Replace all non-word characters by dashes
        $string = preg_replace("/\W/", "-", $string);

        // Replace double dashes by single dashes
        $string = preg_replace("/-+/", '-', $string);

        // Trim dashes from the beginning and end of string
        $string = trim($string, '-');

        return $string;
    }

    static public function slugify3($str, $replace = array(), $delimiter = '-') {
        if (!empty($replace)) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }
    
    /**
     * Convert the date result by current language
     */
    static function translate($array, $lang, $fallbackLang)
    {
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $array[$key] = Helper::translate($val, $lang, $fallbackLang);
            } else {
                
                //pattern: nameDe, nameEn...
                $langUcFirst = ucfirst($lang);
                if (preg_match('~' . $langUcFirst . '$~', $key))
                {
                    
                    if (empty ($val))
                    {
                        $fallbackLangUcFirst = ucfirst($fallbackLang);
                        $val = $array[preg_replace('~' . $langUcFirst . '$~', $fallbackLangUcFirst, $key)];
                    }
                    
                    $array[preg_replace('~' . $langUcFirst . '$~', '', $key)] = $val;
                }
                
                //pattern: name_de, name_en...
                $langUnderscored = '_'.$lang;
                if (preg_match('~' . $langUnderscored . '$~', $key))
                {
                    if (empty ($val))
                    {
                        $fallbackLangUnderscored = '_'.$fallbackLang;
                        $val = $array[preg_replace('~' . $langUnderscored . '$~', $fallbackLangUnderscored, $key)];
                    }
                
                    $array[preg_replace('~' . $langUnderscored . '$~', '', $key)] = $val;
                }
            }
        }
        
        return $array;
    }

}