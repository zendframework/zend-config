<?php
/**
 * @see       https://github.com/zendframework/zend-config for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-config/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Config\Processor;

use PHPUnit\Framework\TestCase;
use Zend\Config\Config;
use Zend\Config\Processor\Constant as ConstantProcessor;

class ConstantTest extends TestCase
{
    const CONFIG_TEST = 'config';

    public function constantProvider()
    {
        if (! defined('ZEND_CONFIG_PROCESSOR_CONSTANT_TEST')) {
            define('ZEND_CONFIG_PROCESSOR_CONSTANT_TEST', 'test-key');
        }

        // @codingStandardsIgnoreStart
        //                                    constantString,                        constantValue
        return [
            'constant'                    => ['ZEND_CONFIG_PROCESSOR_CONSTANT_TEST', ZEND_CONFIG_PROCESSOR_CONSTANT_TEST],
            'class-constant'              => [__CLASS__ . '::CONFIG_TEST',           self::CONFIG_TEST],
            'class-pseudo-constant'       => [__CLASS__ . '::class',                 self::class],
            'class-pseudo-constant-upper' => [__CLASS__ . '::CLASS',                 self::class],
        ];
        // @codingStandardsIgnoreEnd
    }

    /**
     * @dataProvider constantProvider
     *
     * @param string $constantString
     * @param string $constantValue
     */
    public function testCanResolveConstantValues($constantString, $constantValue)
    {
        $config = new Config(['test' => $constantString], true);

        $processor = new ConstantProcessor();
        $processor->process($config);

        $this->assertEquals($constantValue, $config->get('test'));
    }

    /**
     * @dataProvider constantProvider
     *
     * @param string $constantString
     * @param string $constantValue
     */
    public function testWillNotProcessConstantValuesInKeysByDefault($constantString, $constantValue)
    {
        $config = new Config([$constantString => 'value'], true);
        $processor = new ConstantProcessor();
        $processor->process($config);

        $this->assertNotEquals('value', $config->get($constantValue));
        $this->assertEquals('value', $config->get($constantString));
    }

    /**
     * @dataProvider constantProvider
     *
     * @param string $constantString
     * @param string $constantValue
     */
    public function testCanProcessConstantValuesInKeys($constantString, $constantValue)
    {
        $config = new Config([$constantString => 'value'], true);
        $processor = new ConstantProcessor();
        $processor->enableKeyProcessing();
        $processor->process($config);

        $this->assertEquals('value', $config->get($constantValue));
        $this->assertNotEquals('value', $config->get($constantString));
    }

    public function testKeyProcessingDisabledByDefault()
    {
        $processor = new ConstantProcessor();
        $this->assertAttributeSame(false, 'processKeys', $processor);
    }

    public function testCanEnableKeyProcessingViaConstructorArgument()
    {
        $processor = new ConstantProcessor(true, '', '', true);
        $this->assertAttributeSame(true, 'processKeys', $processor);
    }
}
