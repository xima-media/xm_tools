# Xima Tools
A framework for TYPO3 extensions.

Xima Toola is an extension for the TYPO3 CMS that can facilitate common use cases for your custom extensions.

## Requirements

* PHP 5.3 is supported, PHP 5.4 suggested
* TYPO3 CMS 6.2.*

## Installation
### Via TYPO3 Extension Manager
Install *xm_tools* via the TYPO3 Extension Manager in your TYPO3 installation.

### Manually
1. Clone the project.
2. From the folder *xm_tools*, run `composer install`. 
3. Remove the file "composer.json" (TYPO3 6.2 does currently not seem to support 3rd party composer packages).
3. Copy the folder *xm_tools* into the folder *typoconf/ext* of your TYPO3 installation.

## Activation
1. Activate *Xima Tools* in the Extension Manager.
2. From the *Template* module, add the static template *Xima Tools*.

## Configuration
### Share translations
If you want to share a global dictionary with all your extensions, rename any *Resources\Private\Language\locallang.xlf.dist* file to *.xlf*.
### Share parameters
If you want to share global parameters with all your extensions, rename *parameters.yml.dist* to *parameters.yml*. Add any configuration in [YAML](http://yaml.org/) style.
### TypoScript settings
There are four TypoScript settings which can also be set in the Constants Editor:
* plugin.tx_xmtools.settings.loggingIsEnabled: enable some logging through the *\TYPO3\CMS\Core\Log\Logger*
* plugin.tx_xmtools.settings.devModeIsEnabled: currently not in use
* plugin.tx_xmtools.settings.jsSupportIsEnabled: use the integrated Javascript functions, e.g. serve parameters in Javascript.
* plugin.tx_xmtools.settings.jsL10nIsEnabled: use global and translations from other extensions in Javascript as well.

## More information
* [Documentation](http://xm-tools.readthedocs.org)
* [TYPO3 Extension Repository](http://typo3.org/extensions/repository/view/xm_tools)