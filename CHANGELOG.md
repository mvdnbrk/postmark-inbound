# Changelog

All notable changes to `postmark-inbound` will be documented in this file.

## [Unreleased]

- Added a reject method to Collection class. [`79c1451`](https://github.com/mvdnbrk/postmark-inbound/commit/79c1451b3028ea7170d9b9d068d70e8b84bdcc83)
- Added a filter and values method to Collection class. [`c957ae7`](https://github.com/mvdnbrk/postmark-inbound/commit/c957ae7c132d8f41d9a210260cfae4e80ce25942)

## [2.2.0] - 2018-04-30

### Fixed
- Fixed namespace. [`570c649`](https://github.com/mvdnbrk/postmark-inbound/commit/570c649bb05f135bd346ff8e761394a9c938e7f2), [`73d8080`](https://github.com/mvdnbrk/postmark-inbound/commit/73d8080070fcb2d7fcc978b9629052fcac5f5c1d)
- Fixed typo. [`6752e8b`](https://github.com/mvdnbrk/postmark-inbound/commit/6752e8b534378425a2aa6482333a1b78044d1ce8)

### Removed
- Dropped support for PHP 7.0. [`bec06b8`](https://github.com/mvdnbrk/postmark-inbound/commit/bec06b821783297ec9473e108f38aa9daa6916c1)

## [2.1.1] - 2018-04-25

### Added
- Added has method to Collection class. [`3e012ae`](https://github.com/mvdnbrk/postmark-inbound/commit/3e012aed7f748590267dda149408fef6999c2102)
- Added a test for messages with an empty subject. [`3599155`](https://github.com/mvdnbrk/postmark-inbound/commit/359915511a4a377cf5825e73fc1e88c2d9987d30)

### Fixed
- Fixes getter to check if it has a key. [`2590abb`](https://github.com/mvdnbrk/postmark-inbound/commit/2590abb8e97cfb913bf377440eed653185891a6d)
- Fixes tests to match expected/actual pattern. [`41b7910`](https://github.com/mvdnbrk/postmark-inbound/commit/41b791044a732b301588ea5de5d55a7f9990edf2)

## [2.1.0] - 2018-04-25

### Added
- Added getTimzoneAttribute method. [`278de17`](https://github.com/mvdnbrk/postmark-inbound/commit/278de1734bcd46bd62150860fa536f3e3536170b)
- Added getUtcDateAttribute method. [`832ad99`](https://github.com/mvdnbrk/postmark-inbound/commit/832ad99a4df49f83dc73e6be92e0a63fa6cf7c01)
- Added a test for invalid dates. [`99c31a5`](https://github.com/mvdnbrk/postmark-inbound/commit/99c31a5c6e9452673035cc04ac03897a4528e922)
- Added a test for unknown getters. [`f922f19`](https://github.com/mvdnbrk/postmark-inbound/commit/f922f1998b7800f3acfdfba6873985bed6f9daa7)
- Added property annotations. [`c134957`](https://github.com/mvdnbrk/postmark-inbound/commit/c13495714fabdea25b3585e67954004fabdf77b2)

### Removed
- Removed support for PHP 5.6. [`2400cd8`](https://github.com/mvdnbrk/postmark-inbound/commit/2400cd83dcabaa6d7e86a14ebb98509f28c4c347)

## [2.0.0] - 2017-11-07

- Renames namespace to `Mvdnbrk`. [`d60d576`](https://github.com/mvdnbrk/postmark-inbound/commit/d60d57614976ab1c043a21a81832b82c770244c5)

## [1.0.5] - 2017-11-07

- Updates composer.json. [`972576e`](https://github.com/mvdnbrk/postmark-inbound/commit/972576e423de2e4844e60659a2da83a75c10c080)

## [1.0.4] - 2017-11-07

- Added a test in case of receiving a payload without Attachments. [`9784ed8`](https://github.com/mvdnbrk/postmark-inbound/commit/9784ed8f9ad73d3985d284bde3dbf683fc648c27)
- Updates readme. [`8f88946`](https://github.com/mvdnbrk/postmark-inbound/commit/8f889468315543b4914dc9a2d35ad1859415d465)

## [1.0.3] - 2017-10-10

### Added
- Added getMessageIdFromHeadersAttribute method. [`e3925c6`](https://github.com/mvdnbrk/postmark-inbound/commit/e3925c665b493682911e8394f5d28170d46f0b64)
- Added changeKeyCase method. [`3a19672`](https://github.com/mvdnbrk/postmark-inbound/commit/3a196724a9420aed50abfbf814fbf19b9b73bbc6)
- Added toArray method. [`fefd0b4`](https://github.com/mvdnbrk/postmark-inbound/commit/fefd0b44776e650fc494a98a37645bdbcc976688)

## [1.0.2] - 2017-10-05

### Fixed
- Attachment ContentID can be null. [`d5625b5`](https://github.com/mvdnbrk/postmark-inbound/commit/d5625b5b8a74f8e9c9173409e427bacbcb183650)

## [1.0.1] - 2017-10-04

### Added
- Added test for get with default. [`5339052`](https://github.com/mvdnbrk/postmark-inbound/commit/5339052aac705f807b5442b57a7cfb4377105f16)

### Fixed
- Attachments. [`88b4837`](https://github.com/mvdnbrk/postmark-inbound/commit/88b4837a812b9b9c0d6af7e0a5dad866419b3d00)
- Attachment test. [`1fece9b`](https://github.com/mvdnbrk/postmark-inbound/commit/1fece9bfe862e91ca6bcefdc1300e62cde90ddb3)

## [1.0.0] - 2017-09-06

### Added
- Initial commit [`0e3b70e`](https://github.com/mvdnbrk/postmark-inbound/commit/0e3b70e17eaa64f481b9c3c5e6b151be1f5dc823)
- Added doc blocks. [`43bad343`](https://github.com/mvdnbrk/postmark-inbound/commit/43bad343b58228370a8453c905c81dc47a383321)

### Changed
- Updates README. [`3ec0622`](https://github.com/mvdnbrk/postmark-inbound/commit/3ec0622e5dcc90389c3086cfeeca1a2e59f226e4)
- Updates docblocks [`03c3796`](https://github.com/mvdnbrk/postmark-inbound/commit/03c379657ce0323e2fd98ba95ed4dcb521cfa0da)
- Code style fixes. [`7c8eb86`](https://github.com/mvdnbrk/postmark-inbound/commit/7c8eb86cbf9719fbb568160decf4ae8dc735ce98)
- PHPUnit minimum version set to 5.4.3. [`c2331a4`](https://github.com/mvdnbrk/postmark-inbound/commit/c2331a48557ef88f67b2a7df1176cccf05a2b3e8)

[Unreleased]: https://github.com/mvdnbrk/postmark-inbound/compare/v2.2.0...HEAD
[2.2.0]: https://github.com/mvdnbrk/postmark-inbound/compare/v2.1.1...v2.2.0
[2.1.1]: https://github.com/mvdnbrk/postmark-inbound/compare/v2.1.0...v2.1.1
[2.1.0]: https://github.com/mvdnbrk/postmark-inbound/compare/v2.0.0...v2.1.0
[2.0.0]: https://github.com/mvdnbrk/postmark-inbound/compare/v1.0.5...v2.0.0
[1.0.5]: https://github.com/mvdnbrk/postmark-inbound/compare/v1.0.4...v1.0.5
[1.0.4]: https://github.com/mvdnbrk/postmark-inbound/compare/v1.0.3...v1.0.4
[1.0.3]: https://github.com/mvdnbrk/postmark-inbound/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/mvdnbrk/postmark-inbound/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/mvdnbrk/postmark-inbound/compare/v1.0.0...v1.0.1
