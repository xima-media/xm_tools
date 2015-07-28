-----------------------------------------------------
Xima\\XmTools\\Classes\\Typo3\\Cache\\ApiCacheManager
-----------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Typo3\\Cache

.. php:class:: ApiCacheManager

    Stores and retrieves api data in a file, file name is created using md5.

    .. php:attr:: path

        protected

    .. php:method:: setPath($path)

        :param $path:

    .. php:method:: clear()

    .. php:method:: getFilePath($fileName)

        Creates file name by replacing special chars.

        :param $fileName:
        :returns: string

    .. php:method:: __construct()

    .. php:method:: get($fileName)

        Returns file contents. If file is younger than one day returns its value,
        otherwise false.

        :param $fileName:
        :returns: string|bool

    .. php:method:: write($fileName, $content)

        :param $fileName:
        :param $content:

    .. php:method:: clearCache()

    .. php:method:: sanitizeFileName($fileName)

        :param $fileName:

    .. php:method:: getPath()

    .. php:method:: isFileValid($filePath)

        :param $filePath:

    .. php:method:: getAbsoluteFilePath($fileName)

        :param $fileName:

    .. php:method:: getAbsolutePath()
