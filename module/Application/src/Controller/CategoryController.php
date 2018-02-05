<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategoryController extends AbstractActionController
{
    public function indexAction()
    {
    	//check type
    	$type = $this->params()->fromQuery('type', 1);

    	$this->layout()->setVariable('nav', 'category');

        $viewModel = new ViewModel();
    	if($type != 1) $viewModel->setTemplate('/application/category/index'.$type.'.phtml');
    	
        return $viewModel;
    }
}
