# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 3.0.0 - TBD

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#8](https://github.com/zendframework/zend-config/pull/8) updates the
  code base to work with the v3.0 version of zend-servicemanager. Primarily, this
  involved:
  - Updating the `AbstractConfigFactory` follow the new
    `AbstractFactoryInterface` definition.
  - Updating `ReaderPluginManager` and `WriterPluginManager` to follow the
    changes to `AbstractPluginManager`. In particular, they now take their
    `$invokableClasses` configuration and merge it with the incoming
    configuration before delegating to the parent constructor.
  - Updating `Factory` to pass an empty `ServiceManager` to the plugin managers
    when lazy-loading them.
