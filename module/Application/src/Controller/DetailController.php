<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class DetailController extends AbstractActionController
{
    public function indexAction()
    {
    	var_dump("<pre>", $this->params()->fromRoute());die;
        return new ViewModel();
    }
}
