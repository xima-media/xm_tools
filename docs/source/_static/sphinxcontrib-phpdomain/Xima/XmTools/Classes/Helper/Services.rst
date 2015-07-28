----------------------------------------
Xima\\XmTools\\Classes\\Helper\\Services
----------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Helper

.. php:class:: Services

    Static helper methods, independent of context like TYPO3...

    .. php:method:: loadJson($file, $type = 'array')

        Json laden.

        :type $file: string
        :param $file: Path with filename
        :type $type: string
        :param $type: array|json
        :returns: mixed

    .. php:method:: loadFileContent($file)

        Load file content.

        :type $file: string
        :param $file:
        :returns: string

    .. php:method:: curlLoad($url, $convertJsonToArray = false)

        Loads data with curl.

        :type $url: string
        :param $url:
        :type $convertJsonToArray: bool
        :param $convertJsonToArray:
        :returns: mixed

    .. php:method:: truncate($text, $limit = 200, $end = null, $encodiung = 'UTF-8')

        Truncate.

        :type $text: string
        :param $text:
        :type $limit: int
        :param $limit:
        :param $end:
        :type $encodiung: string
        :param $encodiung:
        :returns: string

    .. php:method:: cleanString($string)

        Bereinigt Strings von Umlauten, Sonder- und Leerzeichen.

        :param $string:
        :returns: string

    .. php:method:: getGeoCoding($city, $postcode, $street, $nr, $sensor = 'false')

        Geokodierung mit Google GeoCoding API V3
        https://developers.google.com/maps/documentation/geocoding/?hl=de.

        :type $city: string
        :param $city:
        :type $postcode: string/int
        :param $postcode:
        :type $street: string
        :param $street:
        :type $nr: string/int
        :param $nr:
        :type $sensor: string
        :param $sensor: ('true'|'false')
        :returns: array

    .. php:method:: reverseGeoCoding($lat, $lng, $sensor = 'false')

        Die Adresse zu Geokoordinaten mittels Google GeoCoding API V3 ermitteln
        https://developers.google.com/maps/documentation/geocoding/?hl=de#ReverseGeocoding.

        :type $lat: float
        :param $lat:
        :type $lng: float
        :param $lng:
        :param $sensor:
        :returns: type array

    .. php:method:: replacePlaceholder($placeholder, $string)

        :type $placeholder: array
        :param $placeholder:
        :type $string: string
        :param $string:
        :returns: string

    .. php:method:: loadCvsAsAssocArray($file, $delimiter = ',')

        :type $file: string
        :param $file:
        :type $delimiter: string
        :param $delimiter:
        :returns: type

    .. php:method:: debug($array, $title = null)

        Print arrays for debugging.

        :type $array: mixed
        :param $array:
        :type $title: string
        :param $title:

    .. php:method:: arrayByKeyToCsv($array, $key, $separator = ', ')

        Converts array values into CSV.

        :type $array: array
        :param $array:
        :type $key: string
        :param $key:
        :type $separator: string
        :param $separator: - Default: ', '
        :returns: string
