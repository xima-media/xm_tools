# Xima Tools
A framework for TYPO3 extensions.

Xima Tools is an extension for the TYPO3 CMS that can facilitate common use cases for your custom extensions.

## Documentation

http://xm-tools.readthedocs.org

## Requirements
See *constraints* section in [ext_emconf.php](blob/master/ext_emconf.php)

## Installation
### Via TYPO3 Extension Manager
Install *xm_tools* via the TYPO3 Extension Manager in your TYPO3 installation.

### From source
1. Clone the project.
2. In the folder *xm_tools*, run `composer install --no-dev`.
3. Remove *composer.json* (TYPO3 6.2 does currently not seem to support 3rd party composer packages).
4. (Remove *docs* folder.)
5. Copy the folder *xm_tools* into the *typoconf/ext* folder of your TYPO3 installation.
6. Activate *Xima Tools* in the Extension Manager.

### Optional
Adding the static template *Xima Tools* is optional. There are four TypoScript settings, which can also be set in the Constants Editor:
* plugin.tx_xmtools.settings.loggingIsEnabled: enable some logging through the *\TYPO3\CMS\Core\Log\Logger*
* plugin.tx_xmtools.settings.devModeIsEnabled: currently not in use
* plugin.tx_xmtools.settings.jsSupportIsEnabled: use the integrated Javascript functions, e.g. serve parameters in Javascript.
* plugin.tx_xmtools.settings.jsL10nIsEnabled: use global and translations from other extensions in Javascript as well.


## More information
* [TYPO3 Extension Repository](http://typo3.org/extensions/repository/view/xm_tools)

## Developer notes

### Documentation

In order to have Api docs available in HTML as well as RST (to reference in the sphinx documentation), you need to have the following packages available on your PATH:
- [sphpdox](https://github.com/EdRush/sphpdox) (PHPDoc to sphinxcontrib-phpdomain)
- [phpDocumentor](http://www.phpdoc.org/)
- [sphinx](http://sphinx-doc.org/) (documentation generator)

Please run the following command before committing: `. makedoc.sh`