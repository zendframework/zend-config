<?php
/**
 * @see       https://github.com/zendframework/zend-config for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-config/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Config\Processor;

use PHPUnit\Framework\TestCase;
use Zend\Config\Processor\Token as TokenProcessor;

/**
 * Majority of tests are in ZendTest\Config\ProcessorTest; this class contains
 * tests covering new functionality and/or specific bugs.
 */
class TokenTest extends TestCase
{
    public function testKeyProcessingDisabledByDefault()
    {
        $processor = new TokenProcessor();
        $this->assertAttributeSame(false, 'processKeys', $processor);
    }

    public function testCanEnableKeyProcessingViaConstructorArgument()
    {
        $processor = new TokenProcessor([], '', '', true);
        $this->assertAttributeSame(true, 'processKeys', $processor);
    }
}
