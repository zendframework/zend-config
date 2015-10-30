# Zend\\Config\\Processor

`Zend\Config\Processor` gives you the ability to perform some operations on a `Zend\Config\Config`
object. The `Zend\Config\Processor` is an interface that defines two methods: `process()` and
`processValue()`. These operations are provided by the following concrete implementations:

- `Zend\Config\Processor\Constant`: manage PHP constant values;
- `Zend\Config\Processor\Filter`: filter the configuration data using `Zend\Filter`;
- `Zend\Config\Processor\Queue`: manage a queue of operations to apply to configuration data;
- `Zend\Config\Processor\Token`: find and replace specific tokens;
- `Zend\Config\Processor\Translator`: translate configuration values in other languages using
`Zend\I18n\Translator`;

Below we reported some examples for each type of processor.

## Zend\\Config\\Processor\\Constant

**Using Zend\\Config\\Processor\\Constant**

This example illustrates the basic use of `Zend\Config\Processor\Constant`:

``` sourceCode
define ('TEST_CONST', 'bar');
// set true to Zend\Config\Config to allow modifications
$config = new Zend\Config\Config(array('foo' => 'TEST_CONST'), true);
$processor = new Zend\Config\Processor\Constant();

echo $config->foo . ',';
$processor->process($config);
echo $config->foo;
```

This example returns the output: `TEST_CONST, bar.`.

## Zend\\Config\\Processor\\Filter

**Using Zend\\Config\\Processor\\Filter**

This example illustrates the basic use of `Zend\Config\Processor\Filter`:

``` sourceCode
use Zend\Filter\StringToUpper;
use Zend\Config\Processor\Filter as FilterProcessor;
use Zend\Config\Config;

$config = new Config(array ('foo' => 'bar'), true);
$upper = new StringToUpper();

$upperProcessor = new FilterProcessor($upper);

echo $config->foo . ',';
$upperProcessor->process($config);
echo $config->foo;
```

This example returns the output: `bar,BAR`.

## Zend\\Config\\Processor\\Queue

**Using Zend\\Config\\Processor\\Queue**

This example illustrates the basic use of `Zend\Config\Processor\Queue`:

``` sourceCode
use Zend\Filter\StringToLower;
use Zend\Filter\StringToUpper;
use Zend\Config\Processor\Filter as FilterProcessor;
use Zend\Config\Processor\Queue;
use Zend\Config\Config;

$config = new Config(array ('foo' => 'bar'), true);
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

This example returns the output: `bar`. The filters in the queue are applied with a *FIFO* logic
(First In, First Out).

## Zend\\Config\\Processor\\Token

**Using Zend\\Config\\Processor\\Token**

This example illustrates the basic use of `Zend\Config\Processor\Token`:

``` sourceCode
// set the Config to true to allow modifications
$config = new Config(array('foo' => 'Value is TOKEN'), true);
$processor = new TokenProcessor();

$processor->addToken('TOKEN', 'bar');
echo $config->foo . ',';
$processor->process($config);
echo $config->foo;
```

This example returns the output: `Value is TOKEN,Value is bar`.

## Zend\\Config\\Processor\\Translator

**Using Zend\\Config\\Processor\\Translator**

This example illustrates the basic use of `Zend\Config\Processor\Translator`:

``` sourceCode
use Zend\Config\Config;
use Zend\Config\Processor\Translator as TranslatorProcessor;
use Zend\I18n\Translator\Translator;

$config = new Config(array('animal' => 'dog'), true);

/*
 * The following mapping would exist for the translation
 * loader you provide to the translator instance
 * $italian = array(
 *     'dog' => 'cane'
 * );
 */

$translator = new Translator();
// ... configure the translator ...
$processor = new TranslatorProcessor($translator);

echo "English: {$config->animal}, ";
$processor->process($config);
echo "Italian: {$config->animal}";
```

This example returns the output: `English: dog, Italian: cane`.
