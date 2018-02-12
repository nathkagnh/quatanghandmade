<?php

namespace Library;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;


/**
 * Class AbstractModule
 */
abstract class AbstractModule implements ConfigProviderInterface, AutoloaderProviderInterface
{
    /**
     * @var String Path of current module
     */
    protected $path;

    /**
     * @var String Namespace of current module
     */
    protected $namespace;

    /**
     * This is to be called by descendant classes with:
     * parent::__construct(__DIR__, __NAMESPACE__)
     *
     * @param $path      string Module path
     * @param $namespace string Module namespace
     */
    public function __construct($path, $namespace)
    {
        $this->path = $path;
        $this->namespace = $namespace;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $config = [];

        foreach (glob($this->path . '/config/*.php') as $filename) {
            $config = array_merge_recursive($config, include $filename);
        }

        return $config;
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    $this->namespace => $this->path . DIRECTORY_SEPARATOR . 'src',
                ],
            ],
        ];
    }
}