<?php

namespace Xima\XmTools\Typo3\Security;

/**
 * SecurityInterface
 *
 * @author Steve Lenz <kontakt@steve-lenz.de>
 * @copyright (c) 2015, Steve Lenz
 * @version 1.0.0
 */
interface SecurityInterface
{

    /**
     * Access only for admin
     *
     * @return bool
     */
    public function adminOnly();

    /**
     * Checks if the current user is IBS-Admin
     *
     * @return bool
     */
    public function isAdmin();

    /**
     * Redirects with access denied flash message
     *
     * @param string $action
     * @param string $controller
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function accessDeniedRedirect($action = null, $controller = null);

}