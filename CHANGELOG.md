# Cryptographer Changelog

Release notes for the Cryptographer Craft CMS plugin.



## Unreleased

### Added
- Added Craft 3 compatibility.
- Added a `cryptographer` service that handles all encryption/decryption operations.
- Added a URL safe `encrypt` Twig filter with a counterpart `decrypt` filter.
- Added URL safe [Hashids](https://hashids.org/) support with Twig filters `hashids_encode` & `hashids_decode`.

### Changed
- Renamed v0.x behaviour `encrypt` filter to `maskLegacy`.
- Renamed v0.x behaviour `decrypt` filter to `unmaskLegacy`.
- Uses `Craft::$app->config->general->securityKey` as the default secret key.
- Removed secret key setting from the Control Panel UI.



## 0.1 - 2015-03-25
- Initial release.