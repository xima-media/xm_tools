# Change Log

## 10.4.10 - 2021-11-02
### Changed
- [Task] Move static typoscript registration
- [Improvement] Move configuration for backend visualizations

## 10.4.9 - 2021-08-25
### Changed
- [Task] Extend ResponsiveImageViewHelper to not process certain types of images

## 10.4.8 - 2021-05-20
### Changed
- [Task] Add ImageViewHelper and Uri.ImageViewHelper

## 10.4.7 - 2021-03-26
### Changed
- [Task] Respect no-cookie setting for YouTube embedding

## 10.4.6 - 2021-02-19
### Changed
- [Improvement] Register global namespace "xmt" for ViewHelpers
- [!!!][Task] Remove method for getting extension configuration

### Fixed
- [Bugfix] Fix getting TypoScript Plugin Setup via BackendConfigurationManager

## 10.4.5 - 2021-02-12
### Fixed
- [Bugfix] Fix getting TypoScript Plugin Setup via BackendConfigurationManager

## 10.4.4 - 2021-02-12
### Changed
- [Task] Replace config.persistence.classes typoscript configuration.

### Fixed
- [Bugfix] Fix getting TypoScript Plugin Setup
- [Bugfix] Fix SendMail Service

## 10.4.3 - 2021-01-05
### Fixed
- [Bugfix] Adapt check for image source in ResponsiveImageViewHelper

## 10.4.2 - 2020-12-21
### Changed
- [Task] Apply TYPO3-Rector rules

### Fixed
- [Bugfix] Use correct namespace for class FlexFormService
- [Bugfix] Remove unnecessary re-declarations

## 10.4.1 - 2020-12-18
### Changed
- [Task] Apply TYPO3-Rector rules

## 10.4.0 - 2020-10-30
### Changed
- [Task] Raise compatibility to TYPO3 CMS 10 LTS
- [Task] Remove deprecated classes

## 9.5.12 - 2020-10-30
### Changed
- [Task] Keep backward compatible to old namespace for TtContentRepository

### Fixed
- [Bugfix] Fix namespace for TtContentRepository

------------------------------------------------------------------------
## [Released]
### Changed
- [Task] Set TYPO3 CMS 9 LTS compatibility
- [Task] Update dependencies in ext_emconf.php

### Added
- [Task] Preparing composer.json for testing
- [Task] Add typo3/cms-extensionmanager dependency

### Fixed
- [Bugfix] Invalid composer.json
- [Refactor] Fix code styling issues

### Remove
- [Doc] Remove outdated phpDoc

## 2.4.1 - 2018-05-07
### Added
- [Task] TtContent - add property 'sys_language_uid'

## 2.4.0 - 2018-05-07
### Added
- [Task] Add TtContent to Domain-Model

## 2.3.1 - 2018-03-26
### Added
- [Bugfix] Several fixes of module "responsiveImages" of JavaScript library XIMA.api

## 2.3.0 - 2018-03-12
### Added
- Module "responsiveImages" of JavaScript library XIMA.api

## 2.2.0 - 2018-02-22
- [Task] SendMail should use layoutRootPaths and partialRootPaths
- [Task] Templat format configuration in SendMail::sendTemplateEmail()

## 2.1.0 - 2018-01-24
- [Feature] Support for multiple crop variants

## 2.0.1 - 2018-01-24
- [Doc] Exception annotation added in class Session
- [Bugfix] Sessions will not be removed from database

## 2.0.0 - 2018-01-22
Compatibility for 8 LTS

## 1.0.0 - 2017-02-21
New stable release (without [legacy](https://github.com/xima-media/xm_tools/tree/legacy) code)

### Added
- New ViewHelpers
- Static Session handling
- Static FeUser handling
- DataProcessing
- UserFunctions
- ExtensionManager Utilities

### Changed
- Namespace

### Removed
- legacy features (see [legacy](https://github.com/xima-media/xm_tools/tree/legacy))
