# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 2.6.0 - TBD

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#8](https://github.com/zendframework/zend-config/pull/8) and
  [#18](https://github.com/zendframework/zend-config/pull/18) update the
  code base to make it forwards-compatible with the v3.0 version of
  zend-servicemanager. Primarily, this involved:
  - Updating the `AbstractConfigFactory` to implement the new methods in the
    v3 `AbstractFactoryInterface` definition, and updating the v2 methods to
    proxy to those.
  - Updating `ReaderPluginManager` and `WriterPluginManager` to follow the
    changes to `AbstractPluginManager`. In particular, instead of defining
    invokables, they now define a combination of aliases and factories (using
    the new `InvokableFactory`); additionally, they each now implement both
    `validatePlugin()` from v2 and `validate()` from v3.
