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

class WriterPluginManager extends AbstractPluginManager
{
    protected $instanceof = Writer\AbstractWriter::class;

    protected $invokableClasses = [
        'ini'  => 'Zend\Config\Writer\Ini',
        'json' => 'Zend\Config\Writer\Json',
        'php'  => 'Zend\Config\Writer\PhpArray',
        'yaml' => 'Zend\Config\Writer\Yaml',
        'xml'  => 'Zend\Config\Writer\Xml',
    ];

    public function __construct(ContainerInterface $container, array $config = [])
    {
        $config = array_merge_recursive(['invokables' => $this->invokableClasses], $config);
        parent::__construct($container, $config);
    }
}
