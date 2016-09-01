# xm_tools
A framework for TYPO3 extensions.

xm_tools is an extension for the TYPO3 CMS that can facilitate common use cases for your custom extensions.

## Documentation

http://xm-tools.readthedocs.org

## Requirements
See *constraints* section in [ext_emconf.php](ext_emconf.php)

## Installation
### Via TYPO3 Extension Manager
Install *xm_tools* via the TYPO3 Extension Manager in your TYPO3 installation.

### From source
1. Clone the project.
2. In the folder *xm_tools*, run `composer install --no-dev`.
3. Remove *composer.json* (TYPO3 6.2 does currently not seem to support 3rd party composer packages).
4. (Remove folder *build* and *docs*, files *build.xml*, *makedoc.sh* .)
5. Copy the folder *xm_tools* into the *typoconf/ext* folder of your TYPO3 installation.
6. Activate *Xima Tools* in the Extension Manager.

## xm_tools in the TYPO3 Extension Repository 
* [xm_tools](http://typo3.org/extensions/repository/view/xm_tools)

## Developer notes
* [Changelog](CHANGELOG.md)


### Documentation
In order to have Api docs available in HTML as well as RST (to reference in the sphinx documentation), you need to have the following packages available on your PATH:
- [sphpdox](https://github.com/varspool/sphpdox) (PHPDoc to sphinxcontrib-phpdomain)
- [phpDocumentor](http://www.phpdoc.org/)
- [sphinx](http://sphinx-doc.org/) (documentation generator)

Please run the following command before committing: `. makedoc.sh`
