<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ShoppingController extends AbstractActionController
{
    public function gioHangAction()
    {
    	$this->layout()->setVariable('nav', 'cart');

        $viewModel = new ViewModel();
    	
        return $viewModel;
    }

    public function thanhToanAction()
    {
        $this->layout()->setVariable('nav', 'checkout');

        $viewModel = new ViewModel();
        
        return $viewModel;
    }
}
