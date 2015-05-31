# Documentation information

Required packages for documentation:
- [PHP Coding Standards Fixer](http://cs.sensiolabs.org/)
- [sphinx](http://sphinx-doc.org/) (documentation generator)
- [sphinxcontrib-phpdomain](http://pythonhosted.org/sphinxcontrib-phpdomain/) (sphinx extension for php)
- [sphpdox](https://github.com/EdRush/sphpdox) (PHPDoc to sphinxcontrib-phpdomain)
- [phpDocumentor](http://www.phpdoc.org/)


 Steps to reproduce before pushing changes:
- Fix coding standard: `php-cs-fixer fix Classes/ --verbose`
- Generate sphinx api documentation: `php ../sphpdox/sphpdox.php process --output "docs/source/_static/sphinxcontrib-phpdomain" "Xima\XmTools" Classes`
- Generate php documentation: `phpdoc`
- Generate documentation `(cd docs/; make html)`