<?php
namespace Core;

use Eva\ModuleManager\ModuleManager,
    Eva\EventManager\StaticEventManager,
    Eva\ModuleManager\Feature\AutoloaderProvider;

class Module
{
	/*
    public function init(ModuleManager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'initializeView'), 100);
	}
	 */

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap($e)
    {
        $application        = $e->getParam('application');
        $serviceManager = $application->getServiceManager();
        $helperLoader   = $serviceManager->get('Zend\View\HelperLoader');
		$helperLoader->registerPlugins(array(
			'uri' => 'Eva\View\Helper\Uri'	
		));
    }

    public function getServiceConfiguration()
    {
        return array(
            'factories' => array(
                'db-adapter' =>  function($sm) {
                    $config = $sm->get('config')->db->toArray();
                    $dbAdapter = new \Zend\Db\Adapter\Adapter($config);
                    return $dbAdapter;
                },
            ),
        );
    }
    
	/*
    public function initializeView($e)
    {
        $app          = $e->getParam('application');
        $basePath     = $app->getRequest()->getBasePath();
        $locator      = $app->getLocator();
        $renderer     = $locator->get('Zend\View\Renderer\PhpRenderer');
        $renderer->plugin('url')->setRouter($app->getRouter());
        $renderer->plugin('basePath')->setBasePath($basePath);
	}
	 */
}
