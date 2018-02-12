<?php
namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Admin\Controller\AuthController;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Service\AuthManager;
use Application\Model\User;

/**
 * This is the factory for AuthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class AuthControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authManager = $container->get(AuthManager::class);
        $modelUser = User::getInstance();

        return new AuthController($authManager, $modelUser);
    }
}
