<?php

namespace Xima\XmTools\Classes\Typo3;

/**
 * Helper for FeUser
 *
 * @author Steve Lenz <kontakt@steve-lenz.de>, Sebastian Gierth <sgi@xima.de>
 * @package TYPO3 6.2.x
 * @version 1.2.0
 */
class FeUser
{
    /**
     * @var FeUser
     */
    private $user = NULL;

    /**
     * @var FeUser groupData
     */
    private $groupData = NULL;

    /**
     * Constructor
     */
    public function __construct()
    {
        if ($GLOBALS['TSFE']->fe_user->user) {
            $this->user = $GLOBALS['TSFE']->fe_user->user;
            $this->groupData = $GLOBALS['TSFE']->fe_user->groupData;
        }

    }

    /**
     * @return int
     */
    public function getUid()
    {
        return $this->user['uid'];
    }

    /**
     * @return array
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return array
     */
    public function getGroupData()
    {
        return $this->groupData;
    }

    /**
     * Checks if fe_user is authenticated.
     *
     * @return bool
     */
    public function feUserIsAuthenticated()
    {
        if (null == $this->getUid()) {
            return false;
        }
        return true;
    }

    /**
     * Checks if fe_user is authenticated and has given role.
     *
     * @param string $role
     *
     * @return bool
     */
    public function feUserHasRole($role)
    {
        if ($this->feUserIsAuthenticated() && in_array($role, $this->groupData['title'])) {
            return true;
        }
        return false;
    }

    /**
     * Checks if fe_user is authenticated and has given role id.
     *
     * @param int $roleId
     *
     * @return bool
     */
    public function feUserHasRoleId($roleId)
    {
        if ($this->feUserIsAuthenticated() && in_array($roleId, $this->groupData['uid'])) {
            return true;
        }
        return false;
    }

}
