# Cryptographer Changelog

Release notes for the Cryptographer Craft CMS plugin.


## Unreleased

### Added
- Added `hashidsAlphabet` and `hashidsMinLength` config options. ([#3](https://github.com/miranj/craft-cryptographer/pull/3) by [@oddnavy](https://github.com/oddnavy))



## 1.0.2 - 2019-07-31

### Fixed
- Fixed plugin icon



## 1.0.1 - 2019-07-25

### Changed
- Updated Hashids to 3.0 (on supported environments).

### Fixed
- Fixed an error that prevented the plugin from getting installed. ([#2](https://github.com/miranj/craft-cryptographer/issues/2))



## 1.0.0 - 2019-03-15

### Added
- Added Craft 3 compatibility.
- Added a `cryptographer` service that handles all encryption/decryption operations.
- Added a URL safe `encrypt` Twig filter with a counterpart `decrypt` filter.
- Added URL safe [Hashids](https://hashids.org/) support with Twig filters `maskNumbers` & `unmaskNumbers`.

### Changed
- Renamed v0.x behaviour `encrypt` filter to `maskLegacy`.
- Renamed v0.x behaviour `decrypt` filter to `unmaskLegacy`.
- Uses `Craft::$app->config->general->securityKey` as the default secret key.
- Removed secret key setting from the Control Panel UI.



## 0.1 - 2015-03-25
- Initial release.