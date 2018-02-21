<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ProductController extends AbstractActionController
{
	public function indexAction()
    {
        $this->layout()->setVariable('nav', 'product/home');
        return new ViewModel();
    }

    public function addAction()
    {
        $this->layout()->setVariable('nav', 'product/add');
        // $headScript = $this->getServiceLocator()->get('viewhelpermanager')->get('headScript');
        // $headScript->appendFile('/ad-st/js/scripts/product.js');
        
        return new ViewModel();
    }

    public function editAction()
    {
        $this->layout()->setVariable('nav', 'product/edit');
        return new ViewModel();
    }
}
