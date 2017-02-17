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

    public function testCanResolveClassConstants()
    {
        $key = __CLASS__ . '::CONFIG_TEST';
        $config = new Config([
            'test' => __CLASS__ . '::CONFIG_TEST',
        ], true);

        $processor = new ConstantProcessor();
        $processor->process($config);

        $this->assertEquals(self::CONFIG_TEST, $config->get('test'));
    }

    public function testCanResolveClassPseudoConstant()
    {
        $key = __CLASS__ . '::CONFIG_TEST';
        $config = new Config([
            'test' => __CLASS__ . '::class',
        ], true);

        $processor = new ConstantProcessor();
        $processor->process($config);

        $this->assertEquals(self::class, $config->get('test'));
    }

    public function testCanProcessConstantValuesInKeys()
    {
        if (! defined('ZEND_CONFIG_PROCESSOR_CONSTANT_TEST')) {
            define('ZEND_CONFIG_PROCESSOR_CONSTANT_TEST', 'test-key');
        }

        $config = new Config([
            'ZEND_CONFIG_PROCESSOR_CONSTANT_TEST' => 'value',
        ], true);

        $processor = new ConstantProcessor();
        $processor->process($config);

        $this->assertEquals('value', $config->get(ZEND_CONFIG_PROCESSOR_CONSTANT_TEST));
    }

    public function testCanProcessClassConstantValuesInKeys()
    {
        $key = __CLASS__ . '::CONFIG_TEST';
        $config = new Config([
            $key => 'value',
        ], true);

        $processor = new ConstantProcessor();
        $processor->process($config);

        $this->assertEquals('value', $config->get(self::CONFIG_TEST));
    }

    public function testCanProcessPseudoClassConstantValuesInKeys()
    {
        $key = __CLASS__ . '::class';
        $config = new Config([
            $key => 'value',
        ], true);

        $processor = new ConstantProcessor();
        $processor->process($config);

        $this->assertEquals('value', $config->get(self::class));
    }
}
