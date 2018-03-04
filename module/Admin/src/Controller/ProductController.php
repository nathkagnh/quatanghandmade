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
            $location = 'public/i/products/';
            $allowedExtension = ['image/jpeg', 'image/png', 'image/gif'];
            $image_link = '';
            $other_images_link = [];
            if(in_array($data['image']['type'], $allowedExtension)) $image_link = $this->saveFile($data['image'], $location);
            if(!empty($data['other_images']))
            {
                foreach($data['other_images'] as $file)
                {
                    if(in_array($file['type'], $allowedExtension)) $other_images_link[] = $this->saveFile($file, $location);
                }
            }

            $arrParamsSave = [
                'product_name' => $data['product_name'],
                'original_price' => $data['original_price'],
                'sale_price' => $data['sale_price'],
                'category_id' => $data['category'],
                'discount' => $data['discount'],
                'quantity' => $data['quantity'],
                'description' => $data['description'],
                'detail' => $data['detail'],
                'image' => $image_link,
                'other_images' => json_encode($other_images_link)
            ];

            
        }

        $this->layout()->setVariable('nav', 'product/add');
        return new ViewModel();
    }

    public function editAction()
    {
        $this->layout()->setVariable('nav', 'product/edit');
        return new ViewModel();
    }

    function saveFile($file, $location)
    {
        $filePath = '';
        if(!empty($file))
        {
            $fileName = time().'-'.$file['name'];
            $surfix_date = date('Y/m/d');
            $location .= $surfix_date;
            if(!file_exists($location)) mkdir($location, 0777, true);
            move_uploaded_file($file['tmp_name'], $location.'/'.$fileName);
            $filePath = '/i/products/'.$surfix_date.$fileName;
        }
        return $filePath;
    }


}
