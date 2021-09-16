# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## [1.3.0](https://github.com/tuupola/instrument/compare/1.2.0...1.3.0) - unreleased
### Removed
- Remove internal usage of `tuupola/witchcraft` ([#2](https://github.com/tuupola/instrument/pull/2)).

## [1.2.0](https://github.com/tuupola/instrument/compare/1.1.0...1.2.0) - 2016-10-08
### Added
- Possibility to add multiple tags in one call
- Allow sending single measurement or event

## [1.1.0](https://github.com/tuupola/instrument/compare/1.0.0...1.1.0) - 2016-05-26
### Added
-  Add experimental event support

### Fixed
- Enable increasing and decreasing unset value

## [1.0.0](https://github.com/tuupola/instrument/compare/0.3.0...1.0.0) - 2016-05-20
### Fixed

- Do not throw Exception if stopwatch has not been started
- Add missing methods to the Metric interface

## [0.3.0](https://github.com/tuupola/instrument/compare/0.2.0...0.3.0) - 2016-03-07
### Added

- Allow calling `$timing->set("fly", function () {...})`
- Make stopwatch memory available

## 0.2.0 - 2016-03-06

Initial realese.
