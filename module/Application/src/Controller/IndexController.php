<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$modelUser = User::getInstance();
    	$detailUser = $modelUser->getDetailUser(1);
    	var_dump('<pre style="background-color:#7fbfff;">', $detailUser); exit;

    	$this->layout()->setVariable('nav', 'home');
        return new ViewModel();
    }
}
