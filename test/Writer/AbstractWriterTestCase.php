<?php
/**
 * @see       https://github.com/zendframework/zend-config for the canonical source repository
 * @copyright Copyright (c) 2005-2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-config/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Config\Writer;

use PHPUnit\Framework\TestCase;
use Zend\Config\Config;
use Zend\Config\Exception\InvalidArgumentException;
use Zend\Config\Exception\RuntimeException;

/**
 * @group      Zend_Config
 */
abstract class AbstractWriterTestCase extends TestCase
{
    /**
     * @var \Zend\Config\Reader\ReaderInterface
     */
    protected $reader;

    /**
     *
     * @var \Zend\Config\Writer\WriterInterface
     */
    protected $writer;

    /**
     *
     * @var string
     */
    protected $tmpfile;

    /**
     * Get test asset name for current test case.
     *
     * @return string
     */
    protected function getTestAssetFileName()
    {
        if (empty($this->tmpfile)) {
            $this->tmpfile = tempnam(sys_get_temp_dir(), 'zend-config-writer');
        }
        return $this->tmpfile;
    }

    public function tearDown()
    {
        if (file_exists($this->getTestAssetFileName())) {
            if (! is_writable($this->getTestAssetFileName())) {
                chmod($this->getTestAssetFileName(), 0777);
            }
            @unlink($this->getTestAssetFileName());
        }
    }

    public function testNoFilenameSet()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No file name specified');
        $this->writer->toFile('', '');
    }

    public function testFileNotValid()
    {
        $this->expectException(RuntimeException::class);
        $this->writer->toFile('.', new Config([]));
    }

    public function testFileNotWritable()
    {
        $this->expectException(RuntimeException::class);
        chmod($this->getTestAssetFileName(), 0444);
        $this->writer->toFile($this->getTestAssetFileName(), new Config([]));
    }

    public function testWriteAndRead()
    {
        $config = new Config(['default' => ['test' => 'foo']]);

        $this->writer->toFile($this->getTestAssetFileName(), $config);

        $config = $this->reader->fromFile($this->getTestAssetFileName());

        $this->assertEquals('foo', $config['default']['test']);
    }
}
