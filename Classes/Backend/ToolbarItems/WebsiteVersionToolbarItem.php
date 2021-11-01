<?php


namespace Xima\XmTools\Backend\ToolbarItems;


use TYPO3\CMS\Backend\Toolbar\ToolbarItemInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Package\Exception\UnknownPackageException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\BackendConfigurationManager;
use TYPO3\CMS\Fluid\View\StandaloneView;
use Xima\XmTools\Extensionmanager\ExtensionUtility;

class WebsiteVersionToolbarItem implements ToolbarItemInterface
{
    /**
     * Checks whether the user has access to this toolbar item
     *
     * @return bool TRUE if user has access, FALSE if not
     */
    public function checkAccess()
    {
        return true;
    }

    /**
     * Render "item" part of this toolbar
     *
     * @return string Toolbar item HTML
     */
    public function getItem()
    {
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName('EXT:xm_tools'
            . '/Resources/Private/Backend/Templates/ToolbarItems/WebsiteVersionToolbarItem.html'));

        return $view->assign('websiteVersion', $this->getWebsiteVersion())->render();
    }

    protected function getWebsiteVersion()
    {
        $extKey = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('xm_tools', 'sitepackageExtensionKey');

        if (empty($extKey)) {
            // Fallback to (old) typoscript configuration
            $configurationManager = GeneralUtility::makeInstance(BackendConfigurationManager::class);
            $configurationManager->getDefaultBackendStoragePid();
            $typoScriptSetup = $configurationManager->getTypoScriptSetup();
            $extKey = $typoScriptSetup['module.']['tx_xmtools.']['settings.']['sitepackageExtKey'];
        }

        try {
            return ExtensionUtility::getExtensionVersion($extKey);
        } catch (UnknownPackageException $e) {
            return '';
        }
    }

    /**
     * TRUE if this toolbar item has a collapsible drop down
     *
     * @return bool
     */
    public function hasDropDown()
    {
        return false;
    }

    /**
     * Render "drop down" part of this toolbar
     *
     * @return string Drop down HTML
     */
    public function getDropDown()
    {
        return '';
    }

    /**
     * Returns an array with additional attributes added to containing <li> tag of the item.
     *
     * Typical usages are additional css classes and data-* attributes, classes may be merged
     * with other classes needed by the framework. Do NOT set an id attribute here.
     *
     * array(
     *     'class' => 'my-class',
     *     'data-foo' => '42',
     * )
     *
     * @return array List item HTML attributes
     */
    public function getAdditionalAttributes()
    {
        return [];
    }

    /**
     * Returns an integer between 0 and 100 to determine
     * the position of this item relative to others
     *
     * By default, extensions should return 50 to be sorted between main core
     * items and other items that should be on the very right.
     *
     * @return int 0 .. 100
     */
    public function getIndex()
    {
        return 0;
    }
}
