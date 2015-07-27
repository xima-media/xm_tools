<?php
namespace Xima\XmTools\Classes\Helper;

/**
 * Static helper methods, independent of context like TYPO3...
 *
 * @package xm_tools
 * @author Steve Lenz <sle@xima.de>
 * @author Wolfram Eberius <woe@xima.de>
 *
 */
class Helper
{

    /**
     * Modifies a string to remove all non ASCII characters and spaces.
     */
    public static function slugify($text)
    {
        $table = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'Oe', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'ae', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'oe', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', 'ü' => 'ue',
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

    public static function slugify2($string)
    {
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

    public static function slugify3($str, $replace = array(), $delimiter = '-')
    {
        if (!empty($replace)) {
            $str = str_replace((array) $replace, ' ', $str);
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
    public static function translate($objectToTranslate, $lang, $fallbackLang = '')
    {
        foreach ($objectToTranslate as $key => $value) {

            //knplabs translatables
            if ('translations' === $key && is_array($value)) {
                //fill fallback language first
                if (isset($value[$fallbackLang])) {
                    Helper::mergeTranslations($objectToTranslate, $value[$fallbackLang]);
                }
                if (isset($value[$lang])) {
                    Helper::mergeTranslations($objectToTranslate, $value[$lang]);
                }

                unset($objectToTranslate['translations']);
            } elseif (is_array($value)) {
                $objectToTranslate[$key] = Helper::translate($value, $lang, $fallbackLang);
            } else {
                //pattern: nameDe, nameEn...
                $langUcFirst = ucfirst($lang);
                if (preg_match('~'.$langUcFirst.'$~', $key)) {
                    if (empty($value)) {
                        $fallbackLangUcFirst = ucfirst($fallbackLang);
                        $value = $objectToTranslate[preg_replace('~'.$langUcFirst.'$~', $fallbackLangUcFirst, $key)];
                    }

                    $objectToTranslate[preg_replace('~'.$langUcFirst.'$~', '', $key)] = $value;
                }

                //pattern: name_de, name_en...
                $langUnderscored = '_'.$lang;
                if (preg_match('~'.$langUnderscored.'$~', $key)) {
                    if (empty($value)) {
                        $fallbackLangUnderscored = '_'.$fallbackLang;
                        $value = $objectToTranslate[preg_replace('~'.$langUnderscored.'$~', $fallbackLangUnderscored, $key)];
                    }

                    $objectToTranslate[preg_replace('~'.$langUnderscored.'$~', '', $key)] = $value;
                }
            }
        }

        return $objectToTranslate;
    }

    private function mergeTranslations(&$objectToTranslate, $translations)
    {
        foreach ($translations as $key => $translation) {
            if ($key != 'id') {
                $objectToTranslate[$key] = $translation;
            }
        }
    }

    public static function shortenText($text, $length, $glue = ' ', $finishString = '...')
    {
        $lastPos = strpos($text, ' ', $length) -1;
        $shortenedText = ($lastPos>0) ? substr($text, 0, $lastPos).'...' : $text;

        return $shortenedText;
    }

    /**
     * Convert strings with underscores into CamelCase
     *
     * @param  string $string          The string to convert
     * @param  bool   $first_char_caps camelCase or CamelCase
     * @return string The converted string
     *
     */
    public static function underscoreToCamelCase($string, $first_char_caps = true)
    {
        if ($first_char_caps == true) {
            $string[0] = strtoupper($string[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');

        return preg_replace_callback('/_([a-z])/', $func, $string);
    }

    /**
     * Get the package name of a class
     *
     * @param  mixed  $class
     * @return string
     */
    public static function getClassPackageName($class)
    {
        $name = '';

        if ($class) {
            $reflect  = new \ReflectionClass($class);
            $name = explode('\\', $reflect->getNamespaceName())[1];
        }

        return $name;
    }

    /**
     * Get the short name of a class (class name without namespace)
     *
     * @param  mixed  $class
     * @return string
     */
    public static function getClassShortName($class)
    {
        $name = '';

        if ($class) {
            $reflect  = new \ReflectionClass($class);
            $name = $reflect->getShortName();
        }

        return $name;
    }
}
