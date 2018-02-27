<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;

class ProductController extends AbstractActionController
{
	public function indexAction()
    {
        $this->layout()->setVariable('nav', 'product/home');
        return new ViewModel();
    }

    public function addAction()
    {
        //check is post
        if($this->getRequest()->isPost())
        {
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            //save images
            $surfix_date = date('Y/m/d');
            $location = 'public/i/products/'.$surfix_date;
            $allowedExtension = array('image/jpeg', 'image/png', 'image/gif');
            $image_link = $this->saveFile($data['image'], $location, $allowedExtension);
            $other_images_link = '';
            if(!empty($data['other_images']))
            {
                foreach($data['other_images'] as $file)
                {
                    $other_images_link[] = $this->saveFile($file, $location, $allowedExtension);
                }
            }
        }

        $this->layout()->setVariable('nav', 'product/add');
        return new ViewModel();
    }

    public function editAction()
    {
        $this->layout()->setVariable('nav', 'product/edit');
        return new ViewModel();
    }

    function saveFile($file, $location, $allowedExtension)
    {
        $filePath = '';
        if(!empty($file) && in_array($file['type'], $allowedExtension))
        {
            $fileName = $file['name'];
            if(!file_exists($location)) mkdir($location);
            move_uploaded_file($file['tmp_name'], $location.'/'.$fileName);
            $filePath = '/i/products/'.$surfix_date.$fileName;
        }
        return $filePath;
    }
}
