# Migration to version 3

Version 3 is essentially fully backwards compatible with previous versions, with
one key exception: `Zend\Config\Factory` no longer requires usage of
zend-servicemanager for resolving plugins.

The reason this is considered a backwards compatibility break, however, is due
to signature changes:

- `Factory::setReaderPluginManager()` now accepts a
  `Psr\Container\ContainerInterface`, and not a `Zend\Config\ReaderPluginManager`
  instance; `ReaderPluginManager`, however, still fulfills that typehint.

- `Factory::getReaderPluginManager()` now returns a
  `Psr\Container\ContainerInterface` &mdash; specifically, a
  `Zend\Config\StandaloneReaderPluginManager` &mdash;  and not a
  `Zend\Config\ReaderPluginManager` instance, by default; `ReaderPluginManager`,
  however, still fulfills that typehint.

- `Factory::setWriterPluginManager()` now accepts a
  `Psr\Container\ContainerInterface`, and not a `Zend\Config\WriterPluginManager`
  instance; `WriterPluginManager`, however, still fulfills that typehint.

- `Factory::getWriterPluginManager()` now returns a
  `Psr\Container\ContainerInterface` &mdash; specifically, a
  `Zend\Config\StandaloneWriterPluginManager` &mdash;  and not a
  `Zend\Config\WriterPluginManager` instance, by default; `WriterPluginManager`,
  however, still fulfills that typehint.

If you were extending the class, you will need to update your signatures
accordingly.

This particular update means that you may use any PSR-11 container as a reader
or writer plugin manager, and no longer require installation of
zend-servicemanager to use the plugin manager facilities.
