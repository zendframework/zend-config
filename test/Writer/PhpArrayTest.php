<?php
/**
 * @see       https://github.com/zendframework/zend-config for the canonical source repository
 * @copyright Copyright (c) 2005-2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-config/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Config\Writer;

use Zend\Config\Config;
use Zend\Config\Writer\PhpArray;
use ZendTest\Config\Writer\TestAssets\DummyClassA;
use ZendTest\Config\Writer\TestAssets\DummyClassB;
use ZendTest\Config\Writer\TestAssets\PhpReader;

/**
 * @group      Zend_Config
 */
class PhpArrayTest extends AbstractWriterTestCase
{
    public function setUp()
    {
        $this->writer = new PhpArray();
        $this->reader = new PhpReader();
    }

    /**
     * @group ZF-8234
     */
    public function testRender()
    {
        $config = new Config([
            'test' => 'foo',
            'bar' => [0 => 'baz', 1 => 'foo'],
            'emptyArray' => [],
            'object' => (object) ['foo' => 'bar'],
            'integer' => 123,
            'boolean' => false,
            'null' => null,
        ]);

        $configString = $this->writer->toString($config);

        // build string line by line as we are trailing-whitespace sensitive.
        $expected = "<?php\n";
        $expected .= "return array(\n";
        $expected .= "    'test' => 'foo',\n";
        $expected .= "    'bar' => array(\n";
        $expected .= "        0 => 'baz',\n";
        $expected .= "        1 => 'foo',\n";
        $expected .= "    ),\n";
        $expected .= "    'emptyArray' => array(),\n";
        $expected .= "    'object' => stdClass::__set_state(array(\n";
        $expected .= "   'foo' => 'bar',\n";
        $expected .= ")),\n";
        $expected .= "    'integer' => 123,\n";
        $expected .= "    'boolean' => false,\n";
        $expected .= "    'null' => null,\n";
        $expected .= ");\n";

        $this->assertEquals($expected, $configString);
    }

    public function testRenderWithBracketArraySyntax()
    {
        $config = new Config(['test' => 'foo', 'bar' => [0 => 'baz', 1 => 'foo'], 'emptyArray' => []]);

        $this->writer->setUseBracketArraySyntax(true);
        $configString = $this->writer->toString($config);

        // build string line by line as we are trailing-whitespace sensitive.
        $expected = "<?php\n";
        $expected .= "return [\n";
        $expected .= "    'test' => 'foo',\n";
        $expected .= "    'bar' => [\n";
        $expected .= "        0 => 'baz',\n";
        $expected .= "        1 => 'foo',\n";
        $expected .= "    ],\n";
        $expected .= "    'emptyArray' => [],\n";
        $expected .= "];\n";

        $this->assertEquals($expected, $configString);
    }

    public function testRenderWithQuotesInString()
    {
        $config = new Config(['one' => 'Test with "double" quotes', 'two' => 'Test with \'single\' quotes']);

        $configString = $this->writer->toString($config);

        $expected = "<?php\n";
        $expected .= "return array(\n";
        $expected .= "    'one' => 'Test with \"double\" quotes',\n";
        $expected .= "    'two' => 'Test with \\'single\\' quotes',\n";
        $expected .= ");\n";

        $this->assertEquals($expected, $configString);
    }

    public function testWriteConvertsPathToDirWhenWritingBackToFile()
    {
        $filename = $this->getTestAssetFileName();
        file_put_contents($filename, file_get_contents(__DIR__ . '/_files/array.php'));

        $this->writer->toFile($filename, include $filename);

        // Ensure file endings are same
        $expected = trim(file_get_contents(__DIR__ . '/_files/array.php'));
        $expected = preg_replace("~\r\n|\n|\r~", PHP_EOL, $expected);

        $result = trim(file_get_contents($filename));
        $result = preg_replace("~\r\n|\n|\r~", PHP_EOL, $result);

        $this->assertSame($expected, $result);
    }

    public function testRenderWithClassNameScalarsEnabled()
    {
        $this->writer->setUseClassNameScalars(true);

        $dummyFqnA = DummyClassA::class;
        $dummyFqnB = DummyClassB::class;

        // Dummy classes should not be loaded prior this test
        $message = sprintf('class %s should not be loaded prior test', $dummyFqnA);
        $this->assertFalse(class_exists($dummyFqnA, false), $message);

        $message = sprintf('class %s should not be loaded prior test', $dummyFqnB);
        $this->assertFalse(class_exists($dummyFqnB, false), $message);

        $config = new Config([
            "\\~" => 'bar',
            'PhpArrayTest' => 'PhpArrayTest',
            '' => 'emptyString',
            'TestAssets\DummyClass' => 'foo',
            $dummyFqnA => [
                'fqnValue' => $dummyFqnB
            ],
            '\\' . $dummyFqnA => ''
        ]);

        $expected = <<< ECS
<?php
return array(
    '\\\~' => 'bar',
    'PhpArrayTest' => 'PhpArrayTest',
    '' => 'emptyString',
    'TestAssets\\\\DummyClass' => 'foo',
    \\$dummyFqnA::class => array(
        'fqnValue' => \\$dummyFqnB::class,
    ),
    \\$dummyFqnA::class => '',
);

ECS;
        $result = $this->writer->toString($config);

        $this->assertSame($expected, $result);
    }

    public function testUseClassNameScalarsIsFalseByDefault()
    {
        $this->assertFalse($this->writer->getUseClassNameScalars(), 'useClassNameScalars should be false by default');
    }

    public function testSetUseBracketArraySyntaxReturnsFluentInterface()
    {
        $this->assertSame($this->writer, $this->writer->setUseBracketArraySyntax(true));
    }

    public function testSetUseClassNameScalarsReturnsFluentInterface()
    {
        $this->assertSame($this->writer, $this->writer->setUseClassNameScalars(true));
    }
}
