<?php
/**
 * @see       https://github.com/zendframework/zend-config for the canonical source repository
 * @copyright Copyright (c) 2005-2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-config/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Config\Reader\TestAssets;

use Zend\Config\Reader\ReaderInterface;
use Zend\Config\Exception;

class DummyReader implements ReaderInterface
{
    public function fromFile($filename)
    {
        if (! is_readable($filename)) {
            throw new Exception\RuntimeException("File '{$filename}' doesn't exist or not readable");
        }

        return unserialize(file_get_contents($filename));
    }

    public function fromString($string)
    {
        if (empty($string)) {
            return [];
        }

        return unserialize($string);
    }
}
