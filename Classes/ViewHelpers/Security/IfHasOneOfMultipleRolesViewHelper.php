<?php

namespace Xima\XmTools\Classes\ViewHelpers\Security;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

/**
 * Checks whether the user has one of given roles.
 *
 * @author Steve Lenz <steve.lenz@xima.de>
 *
 * @version 1.0.0
 *
 * @return bool
 */
class IfHasOneOfMultipleRolesViewHelper extends AbstractConditionViewHelper
{
    /**
     * @param string $rolesCsv Role-IDs as csv
     *
     * @return bool
     */
    public function render($rolesCsv)
    {
        $roles = explode(',', $rolesCsv);

        foreach ($roles as $role) {
            if ($this->frontendUserHasRole($role)) {
                return $this->renderThenChild();
            }
        }
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    protected function frontendUserHasRole($role)
    {
        if (!isset($GLOBALS['TSFE']) || !$GLOBALS['TSFE']->loginUser) {
            return false;
        }
        if (is_numeric($role)) {
            return is_array($GLOBALS['TSFE']->fe_user->groupData['uid']) && in_array($role, $GLOBALS['TSFE']->fe_user->groupData['uid']);
        } else {
            return is_array($GLOBALS['TSFE']->fe_user->groupData['title']) && in_array($role, $GLOBALS['TSFE']->fe_user->groupData['title']);
        }
    }
}
