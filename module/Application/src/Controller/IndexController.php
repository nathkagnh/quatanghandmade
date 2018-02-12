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
    	$detailUser = $modelUser->getDetailUserByEmail('nathkagnh@gmail.com');
    	var_dump('<pre style="background-color:#7fbfff;">', $detailUser); exit;

    	// $result = $modelUser->addUser([
    	// 	'user_name' => 'nathkagnh',
    	// 	'email' => 'nathakgnh@gmail.com',
    	// 	'fullname' => 'Lê Đỗ Nhật Khang',
    	// 	'password' => '123456',
    	// 	'status' => 1
    	// ]);
    	// var_dump('<pre style="background-color:#7fbfff;">', $result); exit;

    	$this->layout()->setVariable('nav', 'home');
        return new ViewModel();
    }
}
