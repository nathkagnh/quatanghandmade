<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use Zend\Uri\Uri;

/**
 * This controller is responsible for letting the user to log in and log out.
 */
class AuthController extends AbstractActionController
{
    /**
     * Auth manager.
     * @var User\Service\AuthManager
     */
    private $authManager;

    /**
     * User manager.
     * @var User\Service\UserManager
     */
    private $modelUser;

    /**
     * Constructor.
     */
    public function __construct($authManager, $modelUser)
    {
        $this->authManager = $authManager;
        $this->modelUser = $modelUser;
    }

    /**
     * Authenticates user given email address and password credentials.
     */
    public function loginAction()
    {
        // Retrieve the redirect URL (if passed). We will redirect the user to this
        // URL after successfull login.
        $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
        if(strlen($redirectUrl)>2048)
        {
            throw new \Exception("Too long redirectUrl argument passed");
        }

        // Store login status.
        $isLoginError = false;
        $arrParams = null;

        if($this->getRequest()->isPost())
        {
            //get params
            $arrParams = $this->getRequest()->getPost();

            if(!empty($arrParams['email']) && !empty($arrParams['password']))
            {
                // Perform login attempt.
                $result = $this->authManager->login($arrParams['email'], $arrParams['password'], 0);// email, pass, remember_me

                // Check result.
                if($result->getCode() == Result::SUCCESS)
                {
                    // Get redirect URL.
                    $redirectUrl = $this->params()->fromPost('redirect_url', '');

                    if (!empty($redirectUrl))
                    {
                        // The below check is to prevent possible redirect attack
                        // (if someone tries to redirect user to another domain).
                        $uri = new Uri($redirectUrl);
                        if (!$uri->isValid() || $uri->getHost()!=null)
                            throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                    }

                    // If redirect URL is provided, redirect the user to that URL;
                    // otherwise redirect to Home page.
                    if(empty($redirectUrl))
                    {
                        return $this->redirect()->toRoute('admin-home');
                    }
                    else
                    {
                        $this->redirect()->toUrl($redirectUrl);
                    }
                }
                else $isLoginError = true;
            }
            else $isLoginError = true;
        }

        $viewModel = new ViewModel(array(
            'form' => $arrParams,
            'isLoginError' => $isLoginError,
            'redirectUrl' => $redirectUrl
        ));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    /**
     * The "logout" action performs logout operation.
     */
    public function logoutAction()
    {
        $this->authManager->logout();

        return $this->redirect()->toRoute('admin-login');
    }
}
