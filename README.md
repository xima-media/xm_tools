# xm_tools
A framework for TYPO3 extensions.

xm_tools is an extension for the TYPO3 CMS that can facilitate common use cases for your custom extensions.

## Requirements

* PHP 5.3 is supported, PHP 5.4 suggested
* TYPO3 CMS 6.2.*

## Installation

1. Clone the project.
2. Copy the folder *xm_tools* into the folder *typoconf/ext* of your TYPO3 installation. 
3. Enter the folder *xm_tools* and run `composer install`. 
4. Head to your Backend and add the static template *Xima Tools*.

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

## Documentation

http://xm-tools.readthedocs.org