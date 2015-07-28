----------------------------------------
Xima\\XmTools\\Classes\\Typo3\\FalHelper
----------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Typo3

.. php:class:: FalHelper

    Helper for File Abstraction Layer.

    .. php:method:: downloadFile($uid)

        Download a FAL-File.

        :type $uid: int
        :param $uid: uid of originalFile (originalResource.originalFile.properties.uid)
        :returns: bool|\Xima\XmBildarchiv\Controller\Exception
