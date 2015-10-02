<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Config;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\AbstractPluginManager;

class ReaderPluginManager extends AbstractPluginManager
{
    protected $instanceOf = Reader\ReaderInterface::class;

    /**
     * Default set of readers
     *
     * @var array
     */
    protected $invokableClasses = [
        'ini'             => 'Zend\Config\Reader\Ini',
        'json'            => 'Zend\Config\Reader\Json',
        'xml'             => 'Zend\Config\Reader\Xml',
        'yaml'            => 'Zend\Config\Reader\Yaml',
        'javaproperties'  => 'Zend\Config\Reader\JavaProperties',
    ];

    public function __construct(ContainerInterface $container, array $config = [])
    {
        $config = array_merge_recursive(['invokables' => $this->invokableClasses], $config);
        parent::__construct($container, $config);
    }
}
