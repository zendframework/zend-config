<?php
/**
 * @see       https://github.com/zendframework/zend-config for the canonical source repository
 * @copyright Copyright (c) 2005-2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-config/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Config\Writer\TestAssets;

use Zend\Config\Writer\AbstractWriter;

class DummyWriter extends AbstractWriter
{
    public function processConfig(array $config)
    {
        return serialize($config);
    }
}
