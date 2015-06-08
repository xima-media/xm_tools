--------------------------------------------------------
Xima\\XmTools\\Classes\\API\\REST\\Model\\AbstractEntity
--------------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\API\\REST\\Model

.. php:class:: AbstractEntity

    Base class for models that get constructed by the \Xima\XmTools\Classes\API\REST\Connector. API data is returned as json, converted to an array and then iterated to instantiate model classes.
    Model properties are set by each array, a key in the array will become a property name of the model. If the model class has a parse{key} function for a property, then this function will be called instead of
    setting the data directly.

    <code>
    class Person extends \Xima\XmTools\Classes\API\REST\Model\AbstractApiEntity
    {
    protected $address;
    public function parseAddress($array)
    {
    $address = new Address();
    $address->setStreet($array['street']);
    $address->setZipCode($array['zipCode']);
    $address->setLatitude($array['latitude']);
    $address->setLongitude($array['longitude']);
    $address->setLongitude($array['longitude']);
    $this->address = $address;
    }
    </code>

    .. php:method:: setUid($uid)

        Sets the uid (suggested for the TYPO3 environment).

        :type $uid: int
        :param $uid:

    .. php:method:: parsePropertyArray($array)

        Parses array data as $key => $value in the following manner:
        1. If a parse function for the $key exists, it's getting called.
        2. If a setter function for $key exists, it's getting called.
        3. Otherwise, $value will be set to a property called $key.

        :param $array:
