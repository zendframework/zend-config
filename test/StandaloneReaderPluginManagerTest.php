<?php
/**
 * @see       https://github.com/zendframework/zend-config for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-config/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\Config;

use Psr\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Zend\Config\Exception;
use Zend\Config\Reader;
use Zend\Config\StandaloneReaderPluginManager;

class StandaloneReaderPluginManagerTest extends TestCase
{
    public function supportedConfigExtensions()
    {
        return [
            'ini'            => ['ini', Reader\Ini::class],
            'INI'            => ['INI', Reader\Ini::class],
            'json'           => ['json', Reader\Json::class],
            'JSON'           => ['JSON', Reader\Json::class],
            'xml'            => ['xml', Reader\Xml::class],
            'XML'            => ['XML', Reader\Xml::class],
            'yaml'           => ['yaml', Reader\Yaml::class],
            'YAML'           => ['YAML', Reader\Yaml::class],
            'javaproperties' => ['javaproperties', Reader\JavaProperties::class],
            'javaProperties' => ['javaProperties', Reader\JavaProperties::class],
            'JAVAPROPERTIES' => ['JAVAPROPERTIES', Reader\JavaProperties::class],
        ];
    }

    /**
     * @dataProvider supportedConfigExtensions
     */
    public function testCanRetrieveReaderByExtension($extension, $expectedType)
    {
        $manager = new StandaloneReaderPluginManager();
        $this->assertTrue(
            $manager->has($extension),
            sprintf('Failed to assert plugin manager has plugin %s', $extension)
        );

        $plugin = $manager->get($extension);
        $this->assertInstanceOf($expectedType, $plugin);
    }

    public function supportedConfigClassNames()
    {
        return [
            Reader\Ini::class            => [Reader\Ini::class],
            Reader\Json::class           => [Reader\Json::class],
            Reader\Xml::class            => [Reader\Xml::class],
            Reader\Yaml::class           => [Reader\Yaml::class],
            Reader\JavaProperties::class => [Reader\JavaProperties::class],
        ];
    }

    /**
     * @dataProvider supportedConfigClassNames
     */
    public function testCanRetrieveReaderByPluginClassName($class)
    {
        $manager = new StandaloneReaderPluginManager();
        $this->assertTrue(
            $manager->has($class),
            sprintf('Failed to assert plugin manager has plugin %s', $class)
        );

        $plugin = $manager->get($class);
        $this->assertInstanceOf($class, $plugin);
    }

    public function testGetThrowsExceptionIfPluginNotFound()
    {
        $manager = new StandaloneReaderPluginManager();
        $this->expectException(Exception\PluginNotFoundException::class);
        $this->expectExceptionMessage('Config reader plugin by name bogus not found');
        $manager->get('bogus');
    }
}
