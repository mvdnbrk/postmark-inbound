# Changelog

All Notable changes to `postmark-inbound` will be documented in this file.

## [Unreleased]

## [1.0.4] - 2017-11-07

- Added a test in case of receiving a payload without Attachments.
- Updates readme. [`8f88946`](https://github.com/heyhoo/postmark-inbound/commit/8f889468315543b4914dc9a2d35ad1859415d465)

## [1.0.3] - 2017-10-10

### Added
- Added getMessageIdFromHeadersAttribute method. [`e3925c6`](https://github.com/heyhoo/postmark-inbound/commit/e3925c665b493682911e8394f5d28170d46f0b64)
- Added changeKeyCase method. [`3a19672`](https://github.com/heyhoo/postmark-inbound/commit/3a196724a9420aed50abfbf814fbf19b9b73bbc6)
- Added toArray method. [`fefd0b4`](https://github.com/heyhoo/postmark-inbound/commit/fefd0b44776e650fc494a98a37645bdbcc976688)

## [1.0.2] - 2017-10-05

### Fixed
- Attachment ContentID can be null. [`d5625b5`](https://github.com/heyhoo/postmark-inbound/commit/d5625b5b8a74f8e9c9173409e427bacbcb183650)

## [1.0.1] - 2017-10-04

### Added
- Added test for get with default. [`5339052`](https://github.com/heyhoo/postmark-inbound/commit/5339052aac705f807b5442b57a7cfb4377105f16)

### Fixed
- Attachments. [`88b4837`](https://github.com/heyhoo/postmark-inbound/commit/88b4837a812b9b9c0d6af7e0a5dad866419b3d00)
- Attachment test. [`1fece9b`](https://github.com/heyhoo/postmark-inbound/commit/1fece9bfe862e91ca6bcefdc1300e62cde90ddb3)

## [1.0.0] - 2017-09-06

### Added
- Initial commit [`0e3b70e`](https://github.com/heyhoo/postmark-inbound/commit/0e3b70e17eaa64f481b9c3c5e6b151be1f5dc823)
- Added doc blocks. [`43bad343`](https://github.com/heyhoo/postmark-inbound/commit/43bad343b58228370a8453c905c81dc47a383321)

### Changed
- Updates README. [`3ec0622`](https://github.com/heyhoo/postmark-inbound/commit/3ec0622e5dcc90389c3086cfeeca1a2e59f226e4)
- Updates docblocks [`03c3796`](https://github.com/heyhoo/postmark-inbound/commit/03c379657ce0323e2fd98ba95ed4dcb521cfa0da)
- Code style fixes. [`7c8eb86`](https://github.com/heyhoo/postmark-inbound/commit/7c8eb86cbf9719fbb568160decf4ae8dc735ce98)
- PHPUnit minimum version set to 5.4.3. [`c2331a4`](https://github.com/heyhoo/postmark-inbound/commit/c2331a48557ef88f67b2a7df1176cccf05a2b3e8)

[Unreleased]: https://github.com/heyhoo/postmark-inbound/compare/v1.0.4...HEAD
[1.0.4]: https://github.com/heyhoo/postmark-inbound/compare/v1.0.3...v1.0.4
[1.0.3]: https://github.com/heyhoo/postmark-inbound/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/heyhoo/postmark-inbound/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/heyhoo/postmark-inbound/compare/v1.0.0...v1.0.1
