<?php

namespace Xima\XmTools\Classes\Helper;

/**
 * Static helper methods, independent of context like TYPO3...
 *
 * @author Steve Lenz <sle@xima.de>
 * @author Wolfram Eberius <woe@xima.de>
 * @todo Clean up
 */
class Services
{
  /**
   * Json laden.
   *
   * @param string $file Path with filename
   * @param string $type array|json
   *
   * @return mixed
   */
  public static function loadJson($file, $type = 'array')
  {
      $json = (file_exists($file)) ? file_get_contents($file) : null;

      if ($type == 'array') {
          return json_decode($json, true);
      } else {
          return $json;
      }
  }

  /**
   * Load file content.
   *
   * @param string $file
   *
   * @return string
   */
  public static function loadFileContent($file)
  {
      return (file_exists($file)) ? file_get_contents($file) : null;
  }

  /**
   * Loads data with curl.
   *
   * @param string $url
   * @param bool $convertJsonToArray
   *
   * @return mixed
   */
  public static function curlLoad($url, $convertJsonToArray = false)
  {
      // Daten von der API holen
    try {
        $ch = curl_init();
        $timeout = 0;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $res = curl_exec($ch);
        curl_close($ch);

        if ($convertJsonToArray) {
            $res = json_decode($res, true);
        }
    } catch (\Exception $e) {
        $res['status'] = 'ERROR';
        $res['status']['error']['code'] = $e->getCode();
        $res['status']['error']['msg'] = $e->getMessage();
    }

      return $res;
  }

  /**
   * Truncate.
   *
   * @param string $text
   * @param int $limit
   * @param string $encodiung
   *
   * @return string
   */
  public static function truncate($text, $limit = 200, $end = null, $encodiung = 'UTF-8')
  {
      $text = strip_tags($text);
      if (mb_strlen($text, $encodiung) > $limit) {
          $text = mb_strcut($text, 0, $limit, $encodiung);
          $lastWhitespace = mb_strrpos($text, ' ', 0, $encodiung);
          $text = mb_strcut($text, 0, $lastWhitespace, $encodiung).$end;
      }

      return $text;
  }

  /**
   * Bereinigt Strings von Umlauten, Sonder- und Leerzeichen.
   *
   * @param string $name
   *
   * @return string
   */
  public static function cleanString($string)
  {
      $cleaned = null;
      $search = array('ä', 'Ä', 'ö', 'Ö', 'ü', 'Ü', 'ß', '&szlig;', '&auml;', '&Auml;', '&ouml;', '&Ouml;', '&uuml;', '&Uuml;', ' ', '&');
      $replace = array('ae', 'Ae', 'oe', 'Oe', 'ue', 'Ue', 'ss', 'ss', 'ae', 'Ae', 'oe', 'Oe', 'ue', 'Ue', '-', 'und');
      $cleaned = str_replace($search, $replace, $string);
      $cleaned = preg_replace('#[^a-zA-Z0-9\-]#', '', $cleaned);

      return $cleaned;
  }

  /**
   * Geokodierung mit Google GeoCoding API V3
   * https://developers.google.com/maps/documentation/geocoding/?hl=de.
   *
   * @param string $city
   * @param string/int $postcode
   * @param string $street
   * @param string/int $nr
   * @param string $sensor ('true'|'false')
   *
   * @return array
   */
  public static function getGeoCoding($city, $postcode, $street, $nr, $sensor = 'false')
  {
      $googleUrl = 'https://maps.googleapis.com/maps/api/geocode/json?sensor='.$sensor;
      $urlData = '&address='.urlencode($city).','.urlencode($postcode).','.urlencode($street).urlencode(' '.$nr);
      $jsonGoogle = file_get_contents($googleUrl.$urlData);

      return json_decode($jsonGoogle, true);
  }

  /**
   * Die Adresse zu Geokoordinaten mittels Google GeoCoding API V3 ermitteln
   * https://developers.google.com/maps/documentation/geocoding/?hl=de#ReverseGeocoding.
   *
   * @param float $lat
   * @param float $lng
   *
   * @return type array
   */
  public static function reverseGeoCoding($lat, $lng, $sensor = 'false')
  {
      $googleUrl = 'https://maps.googleapis.com/maps/api/geocode/json?sensor='.$sensor;
      $urlData = '&latlng='.$lat.','.$lng;

      $jsonGoogle = file_get_contents($googleUrl.$urlData);

      return json_decode($jsonGoogle, true);
  }

  /**
   * @param array $placeholder
   * @param string $string
   *
   * @return string
   */
  public static function replacePlaceholder($placeholder, $string)
  {
      foreach ($placeholder as $key => $val) {
          $string = preg_replace('~###+('.$key.')+###~', $val, $string);
      }

      return $string;
  }

  /**
   * @param string $file
   * @param string $delimiter
   *
   * @return type
   */
  public static function loadCvsAsAssocArray($file, $delimiter = ',')
  {
      $array = $fields = array();
      $i = 0;
      $handle = @fopen($file, 'r');
      if ($handle) {
          while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
              if (empty($fields)) {
                  $fields = $row;
                  continue;
              }
              foreach ($row as $k => $value) {
                  $array[$i][$fields[$k]] = $value;
              }
              $i++;
          }
          if (!feof($handle)) {
              return array('error' => 'Error: unexpected fgets() fail');
          }
          fclose($handle);
      }

      return $array;
  }

  /**
   * Print arrays for debugging.
   *
   * @param mixed $array
   * @param string $title
   */
  public static function debug($array, $title = null)
  {
      echo '<pre style="text-align:left;color:#fff;background-color:#323232;padding:10px;width:95%;max-height:400px;overflow-x:auto;font-size:12px;font-family:Consolas,\'Lucida Console\',monospace;">';
      echo $title."\r\n--------------------------------\r\n".print_r($array, true);
      echo '</pre>';
  }

  /**
   * Converts array values into CSV.
   *
   * @param array $array
   * @param string $key
   * @param string $separator - Default: ', '
   *
   * @return string
   */
  public static function arrayByKeyToCsv($array, $key, $separator = ', ')
  {
      // convert types into csv
    $csv = null;
      foreach ($array as $item) {
          $csv .= $separator.$item[$key];
      }

      return substr($csv, strlen($separator));
  }
}
