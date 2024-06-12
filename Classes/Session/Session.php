<?php

namespace Xima\XmTools\Session;

use Exception;
use RuntimeException;

/**
 * Session-Layer for TYPO3 Extbase fe_user session
 *
 * @author Steve Lenz <kontakt@steve-lenz.de>
 * @copyright (c) 2014, Steve Lenz
 */
class Session
{
    /**
     * Write data into fe_user session
     *
     * @param string $key
     * @param mixed $data
     * @param string $type - Either "user" (persistent, bound to fe_users profile) or "ses" (temporary, bound to current session cookie)
     * @throws Exception
     */
    public static function set(string $key, mixed $data, string $type = 'ses'): void
    {
        $GLOBALS['TSFE']->fe_user->setKey(self::isType($type), $key, serialize($data));
        $GLOBALS['TSFE']->fe_user->storeSessionData();
    }

    /**
     * Restore data from fe_user session
     *
     * @param string $key
     * @param string $type - Either "user" (persistent, bound to fe_users profile) or "ses" (temporary, bound to current session cookie)
     * @return mixed
     * @throws Exception
     */
    public static function get(string $key, string $type = 'ses'): mixed
    {
        $sessionData = $GLOBALS['TSFE']->fe_user->getKey(self::isType($type), $key);

        return unserialize($sessionData, ['allowed_classes' => true]);
    }

    /**
     * Checks whether a key in fe_user session exists
     *
     * @param string $key
     * @param string $type - Either "user" (persistent, bound to fe_users profile) or "ses" (temporary, bound to current session cookie)
     * @return bool
     * @throws Exception
     */
    public static function has(string $key, string $type = 'ses'): bool
    {
        return (bool)$GLOBALS['TSFE']->fe_user->getKey(self::isType($type), $key);
    }

    /**
     * Removes fe_user session data by key
     *
     * @param string $key
     * @param string $type - Either "user" (persistent, bound to fe_users profile) or "ses" (temporary, bound to current session cookie)
     * @throws Exception
     */
    public static function remove(string $key, string $type = 'ses'): void
    {
        $GLOBALS['TSFE']->fe_user->setKey(self::isType($type), $key, null);
        $GLOBALS['TSFE']->fe_user->removeSessionData();
    }

    /**
     * Stores fe_user session data in database
     */
    public static function persist(): void
    {
        $GLOBALS['TSFE']->fe_user->storeSessionData();
    }

    /**
     * Fetches fe_user session data from database
     */
    public static function fetch(): void
    {
        $GLOBALS['TSFE']->fe_user->fetchSessionData();
    }

    /**
     * Deletes fe_user session data from database
     */
    public static function delete(): void
    {
        $GLOBALS['TSFE']->fe_user->removeSessionData();
    }

    /**
     * @param string $type Either "user" (persistent, bound to fe_users profile) or "ses" (temporary, bound to current session cookie)
     * @return string
     * @throws Exception
     */
    protected static function isType(string $type): string
    {
        if (!in_array($type, ['ses', 'user'])) {
            throw new RuntimeException('Wrong session type "' . $type . '"!');
        }

        return $type;
    }
}
