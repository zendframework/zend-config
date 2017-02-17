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
}
