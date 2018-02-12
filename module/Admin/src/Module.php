<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;
use Admin\Controller\AuthController;
use Admin\Service\AuthManager;
use Library\AbstractModule;

class Module extends AbstractModule
{
    /**
     * Module constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__, __NAMESPACE__);
    }

    /**
     * This method returns the path to module.config.php file.
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    /**
     * This method is called once the MVC bootstrapping is complete and allows
     * to register event listeners. 
     */
    public function onBootstrap(MvcEvent $event)
    {
        // Get event manager.
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method. 
        $sharedEventManager->attach(AbstractActionController::class, MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);

        /**
         * Add below AND route_layouts => [ %route% => %template/layout% ] to a module to allow route based layout
         *
         * Below example applies layout in [layout/admin => [ %path/to/layout.phtml% ] to all routes starting with
         * "admin*" as defined in the "route_layouts => []" array.
         *
         *  'view_manager' => [
         *      'template_map' => [
         *          'layout/admin' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'view'
         *          . DIRECTORY_SEPARATOR . 'layout' . DIRECTORY_SEPARATOR . 'layout.phtml',
         *      ],
         *  ],
         *
         *  'route_layouts' => [
         *      'admin*' => 'layout/admin',
         *  ],
         */
        $event->getApplication()->getEventManager()->getSharedManager()
        ->attach(AbstractActionController::class, MvcEvent::EVENT_DISPATCH, function (MvcEvent $e) {
            $controller = $e->getTarget();
            $routeName = $e->getRouteMatch()->getMatchedRouteName();
            $config = $e->getApplication()->getServiceManager()->get('config');
            $layoutConfig = isset($config['route_layouts']) ? $config['route_layouts'] : [];

            if (isset($layoutConfig) && count($layoutConfig) > 0) {
                if (isset($layoutConfig[$routeName])) {
                    $controller->layout($layoutConfig[$routeName]);
                } else {
                    $rules = array_keys($layoutConfig);
                    foreach ($rules as $routeRule) {
                        if (fnmatch($routeRule, $routeName, FNM_CASEFOLD)) {
                            $controller->layout($layoutConfig[$routeRule]);
                            break;
                        }
                    }
                }
            }
        }, 100);
    }
    
    /**
     * Event listener method for the 'Dispatch' event. We listen to the Dispatch
     * event to call the access filter. The access filter allows to determine if
     * the current visitor is allowed to see the page or not. If he/she
     * is not authorized and is not allowed to see the page, we redirect the user 
     * to the login page.
     */
    public function onDispatch(MvcEvent $event)
    {
        // Get controller and action to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);

        // Get module name of the controller
        $controllerClass = get_class($controller);
        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));

        // Check to login
        if($moduleNamespace == 'Admin')
        {
            // Set alternative layout
            // $this->layout()->setTemplate('layout/admin');

        	// Convert dash-style action name to camel-case.
	        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));
	        
	        // Get the instance of AuthManager service.
	        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);
	        
	        // Execute the access filter on every controller except AuthController
	        // (to avoid infinite redirect).
	        if($controllerName!=AuthController::class && !$authManager->filterAccess($controllerName, $actionName))
            {
	            // Remember the URL of the page the user tried to access. We will
	            // redirect the user to that URL after successful login.
	            $uri = $event->getApplication()->getRequest()->getUri();
	            // Make the URL relative (remove scheme, user info, host name and port)
	            // to avoid redirecting to other domain by a malicious user.
	            $uri->setScheme(null)->setHost(null)->setPort(null)->setUserInfo(null);
	            $redirectUrl = $uri->toString();
	            
	            // Redirect the user to the "Login" page.
	            return $controller->redirect()->toRoute('admin-login', [], ['query'=>['redirectUrl'=>$redirectUrl]]);
	        }
        }
    }
}
