<?php
/**
 * @see       https://github.com/zendframework/zend-config for the canonical source repository
 * @copyright Copyright (c) 2005-2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-config/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Config\Writer;

use Zend\Config\Config;
use Zend\Config\Reader\Xml as XmlReader;
use Zend\Config\Writer\Xml as XmlWriter;

/**
 * @group      Zend_Config
 */
class XmlTest extends AbstractWriterTestCase
{
    public function setUp()
    {
        $this->writer = new XmlWriter();
        $this->reader = new XmlReader();
    }

    public function testToString()
    {
        $config = new Config(['test' => 'foo', 'bar' => [0 => 'baz', 1 => 'foo']]);

        $configString = $this->writer->toString($config);

        $expected = <<<ECS
<?xml version="1.0" encoding="UTF-8"?>
<zend-config>
    <test>foo</test>
    <bar>baz</bar>
    <bar>foo</bar>
</zend-config>

ECS;

        $this->assertEquals($expected, $configString);
    }

    public function testSectionsToString()
    {
        $config = new Config([], true);
        $config->production = [];

        $config->production->webhost = 'www.example.com';
        $config->production->database = [];
        $config->production->database->params = [];
        $config->production->database->params->host = 'localhost';
        $config->production->database->params->username = 'production';
        $config->production->database->params->password = 'secret';
        $config->production->database->params->dbname = 'dbproduction';

        $configString = $this->writer->toString($config);

        $expected = <<<ECS
<?xml version="1.0" encoding="UTF-8"?>
<zend-config>
    <production>
        <webhost>www.example.com</webhost>
        <database>
            <params>
                <host>localhost</host>
                <username>production</username>
                <password>secret</password>
                <dbname>dbproduction</dbname>
            </params>
        </database>
    </production>
</zend-config>

ECS;

        $expected = str_replace("\r\n", "\n", $expected);
        $this->assertEquals($expected, $configString);
    }

    /**
     * @group 6797
     */
    public function testAddBranchProperyConstructsSubBranchesOfTypeNumeric()
    {
        $config = new Config([], true);
        $config->production = [['foo'], ['bar']];

        $configString = $this->writer->toString($config);

        $expected = <<<ECS
<?xml version="1.0" encoding="UTF-8"?>
<zend-config>
    <production>foo</production>
    <production>bar</production>
</zend-config>

ECS;

        $this->assertEquals($expected, $configString);
    }
}
