<?php

namespace Xima\XmTools\Classes\Typo3\Controller;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Wolfram Eberius <woe@xima.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * AbstractController.
 */
abstract class AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @var \Xima\XmTools\Classes\Typo3\SessionManager
     * @inject
     */
    protected $session = null;

    /**
     * @var \Xima\XmTools\Classes\Typo3\Services
     * @inject
     */
    protected $typo3Services;

    protected function assignDefaultVariables()
    {
        $this->view->assign('lang', $this->typo3Services->getLang());
        $this->view->assign('settings', $this->settings);
    }

    /**
     * Adds stylesheets and javascripts to page head by action.
     *
     * @param string $action The current action.
     *
     * @return bool
     */
    protected function addAssets($action)
    {
        $settings = $this->typo3Services->getExtension()->getSettings();
        $js = (is_array($settings['js'][$action]) ? $settings['js'][$action] : array());
        $css = (is_array($settings['css'][$action]) ? $settings['css'][$action] : array());

        if (is_array($settings['js']['l10n'][$action][$this->typo3Services->getLang()])) {
            $js = array_merge($js, $settings['js']['l10n'][$action][$this->typo3Services->getLang()]);
        }
        if (is_array($settings['css']['l10n'][$action][$this->typo3Services->getLang()])) {
            $css = array_merge($css, $settings['css']['l10n'][$action][$this->typo3Services->getLang()]);
        }
        if (!empty($js)) {
            $this->typo3Services->includeJavaScript($js);
        }
        if (!empty($css)) {
            $this->typo3Services->includeCss($css);
        }

        return true;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }

    public function getTypo3Services()
    {
        return $this->typo3Services;
    }

    public function setTypo3Services($typo3Services)
    {
        $this->typo3Services = $typo3Services;

        return $this;
    }
}
