<?php
/**
 * @see       https://github.com/zendframework/zend-config for the canonical source repository
 * @copyright Copyright (c) 2005-2019 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-config/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Config\Reader;

use Zend\Config\Exception;
use Zend\Config\Reader\Ini;

/**
 * @group      Zend_Config
 */
class IniTest extends AbstractReaderTestCase
{
    public function setUp()
    {
        $this->reader = new Ini();
    }

    /**
     * getTestAssetPath(): defined by AbstractReaderTestCase.
     *
     * @see    AbstractReaderTestCase::getTestAssetPath()
     * @return string
     */
    protected function getTestAssetPath($name)
    {
        return __DIR__ . '/TestAssets/Ini/' . $name . '.ini';
    }

    public function testInvalidIniFile()
    {
        $this->reader = new Ini();
        $this->expectException(Exception\RuntimeException::class);
        $arrayIni = $this->reader->fromFile($this->getTestAssetPath('invalid'));
    }

    public function testFromString()
    {
        $ini = <<<ECS
test= "foo"
bar[]= "baz"
bar[]= "foo"

ECS;

        $arrayIni = $this->reader->fromString($ini);
        $this->assertEquals('foo', $arrayIni['test']);
        $this->assertEquals('baz', $arrayIni['bar'][0]);
        $this->assertEquals('foo', $arrayIni['bar'][1]);
    }

    public function testInvalidString()
    {
        $ini = <<<ECS
test== "foo"

ECS;
        $this->expectException(Exception\RuntimeException::class);
        $arrayIni = $this->reader->fromString($ini);
    }

    public function testFromStringWithSection()
    {
        $ini = <<<ECS
[all]
test= "foo"
bar[]= "baz"
bar[]= "foo"

ECS;

        $arrayIni = $this->reader->fromString($ini);
        $this->assertEquals('foo', $arrayIni['all']['test']);
        $this->assertEquals('baz', $arrayIni['all']['bar'][0]);
        $this->assertEquals('foo', $arrayIni['all']['bar'][1]);
    }

    public function testFromStringNested()
    {
        $ini = <<<ECS
bla.foo.bar = foobar
bla.foobar[] = foobarArray
bla.foo.baz[] = foobaz1
bla.foo.baz[] = foobaz2

ECS;

        $arrayIni = $this->reader->fromString($ini);
        $this->assertEquals('foobar', $arrayIni['bla']['foo']['bar']);
        $this->assertEquals('foobarArray', $arrayIni['bla']['foobar'][0]);
        $this->assertEquals('foobaz1', $arrayIni['bla']['foo']['baz'][0]);
        $this->assertEquals('foobaz2', $arrayIni['bla']['foo']['baz'][1]);
    }

    public function testFromFileParseSections()
    {
        $arrayIni = $this->reader->fromFile($this->getTestAssetPath('sections'));

        $this->assertEquals('production', $arrayIni['production']['env']);
        $this->assertEquals('foo', $arrayIni['production']['production_key']);
        $this->assertEquals('staging', $arrayIni['staging : production']['env']);
        $this->assertEquals('bar', $arrayIni['staging : production']['staging_key']);
    }

    public function testFromFileDontParseSections()
    {
        $reader = $this->reader;
        $reader->setProcessSections(false);

        $arrayIni = $reader->fromFile($this->getTestAssetPath('sections'));

        $this->assertEquals('staging', $arrayIni['env']);
        $this->assertEquals('foo', $arrayIni['production_key']);
        $this->assertEquals('bar', $arrayIni['staging_key']);
    }

    public function testFromFileIgnoresNestingInSectionNamesWhenSectionsNotProcessed()
    {
        $reader = $this->reader;
        $reader->setProcessSections(false);

        $arrayIni = $reader->fromFile($this->getTestAssetPath('nested-sections'));

        $this->assertArrayNotHasKey('environments.production', $arrayIni);
        $this->assertArrayNotHasKey('environments.staging', $arrayIni);
        $this->assertArrayNotHasKey('environments', $arrayIni);
        $this->assertArrayNotHasKey('production', $arrayIni);
        $this->assertArrayNotHasKey('staging', $arrayIni);
        $this->assertEquals('staging', $arrayIni['env']);
        $this->assertEquals('foo', $arrayIni['production_key']);
        $this->assertEquals('bar', $arrayIni['staging_key']);
    }

    public function testFromStringParseSections()
    {
        $ini = <<<ECS
[production]
env='production'
production_key='foo'

[staging : production]
env='staging'
staging_key='bar'

ECS;
        $arrayIni = $this->reader->fromString($ini);

        $this->assertEquals('production', $arrayIni['production']['env']);
        $this->assertEquals('foo', $arrayIni['production']['production_key']);
        $this->assertEquals('staging', $arrayIni['staging : production']['env']);
        $this->assertEquals('bar', $arrayIni['staging : production']['staging_key']);
    }

    public function testFromStringDontParseSections()
    {
        $ini = <<<ECS
[production]
env='production'
production_key='foo'

[staging : production]
env='staging'
staging_key='bar'

ECS;
        $reader = $this->reader;
        $reader->setProcessSections(false);

        $arrayIni = $reader->fromString($ini);

        $this->assertEquals('staging', $arrayIni['env']);
        $this->assertEquals('foo', $arrayIni['production_key']);
        $this->assertEquals('bar', $arrayIni['staging_key']);
    }

    public function testFromStringIgnoresNestingInSectionNamesWhenSectionsNotProcessed()
    {
        $ini = <<<ECS
[environments.production]
env='production'
production_key='foo'

[environments.staging]
env='staging'
staging_key='bar'
ECS;
        $reader = $this->reader;
        $reader->setProcessSections(false);

        $arrayIni = $reader->fromString($ini);

        $this->assertArrayNotHasKey('environments.production', $arrayIni);
        $this->assertArrayNotHasKey('environments.staging', $arrayIni);
        $this->assertArrayNotHasKey('environments', $arrayIni);
        $this->assertArrayNotHasKey('production', $arrayIni);
        $this->assertArrayNotHasKey('staging', $arrayIni);
        $this->assertEquals('staging', $arrayIni['env']);
        $this->assertEquals('foo', $arrayIni['production_key']);
        $this->assertEquals('bar', $arrayIni['staging_key']);
    }
}
