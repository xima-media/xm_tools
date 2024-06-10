<?php

namespace Xima\XmTools\Security;

/**
 * Layer for the TYPO3 fe_user
 *
 * @author Steve Lenz <kontakt@steve-lenz.de>
 * @copyright (c) 2015, Steve Lenz
 */
class FeUser
{
    /**
     * Checks whether fe_user exists
     *
     * @return bool
     */
    public static function isFeUser(): bool
    {
        return (bool)$GLOBALS['TSFE']->fe_user->user;
    }

    /**
     * Returns the fe_user uid
     *
     * @return int
     */
    public static function getUid(): int
    {
        return $GLOBALS['TSFE']->fe_user->user['uid'] ?? 0;
    }

    /**
     * Returns fe_user data
     *
     * @param null $key - If null you get the whole user data array
     * @return array|false - false if key not exists
     */
    public static function getUser($key = null): array|false
    {
        if (null === $key) {
            return $GLOBALS['TSFE']->fe_user->user;
        }
        return $GLOBALS['TSFE']->fe_user->user[$key] ?? false;
    }

    /**
     * Returns the whole fe_user group data
     *
     * @return array
     */
    public static function getGroupData(): array
    {
        return $GLOBALS['TSFE']->fe_user->groupData;
    }

    /**
     * Checks whether fe_user is authenticated
     *
     * @return bool
     */
    public static function isAuthenticated(): bool
    {
        return self::isFeUser() && 0 !== self::getUid();
    }

    /**
     * Checks whether fe_user is authenticated and has given role
     *
     * @param string $role
     * @return bool
     */
    public static function hasRole(string $role): bool
    {
        return self::isFeUser()
            && self::isAuthenticated()
            && in_array($role, $GLOBALS['TSFE']->fe_user->groupData['title'])
        ;
    }

    /**
     * Checks whether fe_user is authenticated and has given role id
     *
     * @param int $role
     * @return bool
     */
    public static function hasRoleId(int $role): bool
    {
        return self::isFeUser()
            && self::isAuthenticated()
            && in_array($role, $GLOBALS['TSFE']->fe_user->groupData['uid'])
        ;
    }
}
