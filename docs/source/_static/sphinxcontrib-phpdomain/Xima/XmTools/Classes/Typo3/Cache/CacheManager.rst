--------------------------------------------------
Xima\\XmTools\\Classes\\Typo3\\Cache\\CacheManager
--------------------------------------------------

.. php:namespace: Xima\\XmTools\\Classes\\Typo3\\Cache

.. php:class:: CacheManager

    Stores and retrieves data in a file.

    .. php:attr:: path

        protected

    .. php:method:: __construct()

    .. php:method:: get($fileName)

        Returns file contents. If file is younger than one day returns its value,
        otherwise false.

        :param $fileName:
        :returns: string|bool

    .. php:method:: write($fileName, $content)

        :param $fileName:
        :param $content:

    .. php:method:: setPath($path)

        :param $path:

    .. php:method:: clearCache()

    .. php:method:: getFilePath($fileName)

        Creates file name by replacing special chars.

        :param $fileName:
        :returns: string

    .. php:method:: sanitizeFileName($fileName)

        :param $fileName:

    .. php:method:: getPath()

    .. php:method:: isFileValid($filePath)

        :param $filePath:

    .. php:method:: getAbsoluteFilePath($fileName)

        :param $fileName:

    .. php:method:: getAbsolutePath()
