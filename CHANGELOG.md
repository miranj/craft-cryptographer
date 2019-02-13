# Cryptographer Changelog

Release notes for the Cryptographer Craft CMS plugin.



## Unreleased

### Added
- Added Craft 3 compatibility.
- Added a `cryptographer` service that handles all encryption/decryption operations.
- Added a URL safe `encrypt` Twig filter with a counterpart `decrypt` filter.

### Changed
- Renamed v0.x behaviour `encrypt` filter to `encrypt_legacy`.
- Renamed v0.x behaviour `decrypt` filter to `decrypt_legacy`.
- Uses `Craft::$app->config->general->securityKey` as the default secret key.



## 0.1 - 2015-03-25
- Initial release.