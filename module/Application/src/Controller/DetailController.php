<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DetailController extends AbstractActionController
{
    public function indexAction()
    {
    	//check type
    	$type = $this->params()->fromQuery('type', 1);

    	$this->layout()->setVariable('nav', 'product');

    	$viewModel = new ViewModel();
    	if($type == 2) $viewModel->setTemplate('/application/detail/index2.phtml');
    	
        return $viewModel;
    }
}
