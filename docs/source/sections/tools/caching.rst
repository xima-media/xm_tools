Caching
-------

The :php:class:`CacheManager` offers a simple way to write and restore data to the file system. Currently, a cache counts as valid when it's not older than 24 hours.
See :php:meth:`CacheManager::write` and :php:meth:`CacheManager::get`.