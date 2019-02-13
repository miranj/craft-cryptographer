# Cryptographer Changelog

Release notes for the Cryptographer Craft CMS plugin.



## Unreleased

### Added
- Added Craft 3 compatibility.
- Added a `cryptographer` service that handles all encryption/decryption operations.

### Changed
- Renamed `encrypt` to `encrypt_legacy`.
- Renamed `decrypt` to `decrypt_legacy`.
- Uses `Craft::$app->config->general->securityKey` as the default secret key.



## 0.1 - 2015-03-25
- Initial release.