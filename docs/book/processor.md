# Zend\\Config\\Processor

`Zend\Config\Processor` provides the ability to perform operations on a
`Zend\Config\Config` object. `Zend\Config\Processor` is itself an interface that
defining two methods: `process()` and `processValue()`.

zend-config provides the following concrete implementations:

- `Zend\Config\Processor\Constant`: manage PHP constant values.
- `Zend\Config\Processor\Env`: manage PHP getenv() function values.
- `Zend\Config\Processor\Filter`: filter the configuration data using `Zend\Filter`.
- `Zend\Config\Processor\Queue`: manage a queue of operations to apply to configuration data.
- `Zend\Config\Processor\Token`: find and replace specific tokens.
- `Zend\Config\Processor\Translator`: translate configuration values in other languages using `Zend\I18n\Translator`.

> ### What gets processed?
>
> Typically, you will process configuration _values_. However, there are use
> cases for supplying constant and/or token _keys_; one common one is for
> using class-based constants as keys to avoid using magic "strings":
>
> ```json
> {
>     "Acme\\Compoment::CONFIG_KEY": {}
> }
> ```
>
> As such, as of version 3.1.0, the `Constant` and `Token` processors can
> optionally also process the keys of the `Config` instance provided to them, by
> calling `enableKeyProcessing()` on their instances, or passing a boolean
> `true` value for the fourth constructor argument.

## Zend\\Config\\Processor\\Constant

### Using Zend\\Config\\Processor\\Constant

This example illustrates the basic usage of `Zend\Config\Processor\Constant`:

```php
define ('TEST_CONST', 'bar');

// Provide the second parameter as boolean true to allow modifications:
$config = new Zend\Config\Config(['foo' => 'TEST_CONST'], true);
$processor = new Zend\Config\Processor\Constant();

echo $config->foo . ',';
$processor->process($config);
echo $config->foo;
```

This example returns the output: `TEST_CONST,bar`.

As of version 3.1.0, you can also tell the `Constant` processor to process keys:

```php
// At instantiation:
$processor = new Zend\Config\Processor\Constant(true, '', '', true);

// Or later, via a method call:
$processor->enableKeyProcessing();
```
When enabled, any constant values found in keys will also be replaced.

## Zend\\Config\\Processor\\Env

### Using Zend\\Config\\Processor\\Env

This example illustrates the basic usage of `Zend\Config\Processor\Env`:

```php
putenv('AMQP_PASSWORD=guest');

use Zend\Config\Config;
use Zend\Config\Factory;
use Zend\Config\Processor\Env as EnvProcessor;

$config = new Config([
            'host' => '127.0.0.1',
            'port' => 5672,
            'username' => 'guest',
            'password' => 'env(AMQP_PASSWORD)',
            'vhost' => '/',
        ], true);

$processor = new EnvProcessor;
$processor->process($config);
$config->setReadOnly();

echo $config->amqp->password;
```

This example returns the output: `guest`.


## Zend\\Config\\Processor\\Filter

### Using Zend\\Config\\Processor\\Filter

This example illustrates basic usage of `Zend\Config\Processor\Filter`:

```php
use Zend\Filter\StringToUpper;
use Zend\Config\Processor\Filter as FilterProcessor;
use Zend\Config\Config;

// Provide the second parameter as boolean true to allow modifications:
$config = new Config(['foo' => 'bar'], true);
$upper = new StringToUpper();

$upperProcessor = new FilterProcessor($upper);

echo $config->foo . ',';
$upperProcessor->process($config);
echo $config->foo;
```

This example returns the output: `bar,BAR`.

## Zend\\Config\\Processor\\Queue

### Using Zend\\Config\\Processor\\Queue

This example illustrates basic usage of `Zend\Config\Processor\Queue`:

```php
use Zend\Filter\StringToLower;
use Zend\Filter\StringToUpper;
use Zend\Config\Processor\Filter as FilterProcessor;
use Zend\Config\Processor\Queue;
use Zend\Config\Config;

// Provide the second parameter as boolean true to allow modifications:
$config = new Config(['foo' => 'bar'], true);
$upper  = new StringToUpper();
$lower  = new StringToLower();

$lowerProcessor = new FilterProcessor($lower);
$upperProcessor = new FilterProcessor($upper);

$queue = new Queue();
$queue->insert($upperProcessor);
$queue->insert($lowerProcessor);
$queue->process($config);

echo $config->foo;
```

This example returns the output: `bar`. The filters in the queue are applied in
*FIFO* (First In, First Out) order .

## Zend\\Config\\Processor\\Token

### Using Zend\\Config\\Processor\\Token

This example illustrates basic usage of `Zend\Config\Processor\Token`:

```php
// Provide the second parameter as boolean true to allow modifications:
$config = new Config(['foo' => 'Value is TOKEN'], true);
$processor = new TokenProcessor();

$processor->addToken('TOKEN', 'bar');
echo $config->foo . ',';
$processor->process($config);
echo $config->foo;
```

This example returns the output: `Value is TOKEN,Value is bar`.

As of version 3.1.0, you can also tell the `Constant` processor to process keys:

```php
// At instantiation:
$processor = new Zend\Config\Processor\Token($tokens, '', '', true);

// Or later, via a method call:
$processor->enableKeyProcessing();
```

When enabled, any token values found in keys will also be replaced.

## Zend\\Config\\Processor\\Translator

### Using Zend\\Config\\Processor\\Translator

This example illustrates basic usage of `Zend\Config\Processor\Translator`:

```php
use Zend\Config\Config;
use Zend\Config\Processor\Translator as TranslatorProcessor;
use Zend\I18n\Translator\Translator;

// Provide the second parameter as boolean true to allow modifications:
$config = new Config(['animal' => 'dog'], true);

/*
 * The following mapping is used for the translation
 * loader provided to the translator instance:
 *
 * $italian = [
 *     'dog' => 'cane'
 * ];
 */

$translator = new Translator();
// ... configure the translator ...
$processor = new TranslatorProcessor($translator);

echo "English: {$config->animal}, ";
$processor->process($config);
echo "Italian: {$config->animal}";
```

This example returns the output: `English: dog,Italian: cane`.