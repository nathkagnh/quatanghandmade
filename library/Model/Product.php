<?php

/**
 * @Author: KhangLDN
 * @Date:   2018-02-07 14:18:31
 * @Last Modified by:   KhangLDN
 * @Last Modified time: 2018-02-08 17:14:23
 */

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use PDO;

class Product
{
	//model instance
	protected static $_instance = null;
	private $_adapter = null;

	public function __construct()
    {
    	$this->_adapter = new Adapter([
            'driver' => 'Pdo_Mysql',
            'database' => 'qthm',
            'username' => 'root',
            'password' => '',
            'hostname' => 'localhost',
            'port' => 3306,
            'driver_options' => [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8']
    	]);
    }

    public static function _destruct()
    {
        self::$_instance = null;
    }

    public static function getInstance()
    {
        //check instance
        if (is_null(self::$_instance))
        {
            self::$_instance = new self();
        }

        //return instance
        return self::$_instance;
    }

    public function insertProduct($arrParams)
    {
        $arrParamsDefault = [
            'product_name' => null,
            'url' => null,
            'original_price' => 0,
            'discount' => 0,
            'sale_price' => 0,
            'category_id' => null,
            'status' => 1,
            'image' => null,
            'description' => null,
            'detail' => null,
            'quantity' => null,
            'params' => null,
            'has_gift' => null,
            'gift_title' => null,
            'gift_image' => null,
            'gift_description' => null,
            'other_images' => null
        ];
        $arrParams = array_merge($arrParamsDefault, $arrParams);

    	$result = false;
    	$stmt = $this->_adapter->createStatement();
    	$stmt->prepare('CALL sp_insertProduct(:p_product_name, :p_url, :p_original_price, :p_discount, :p_sale_price, :p_category_id, :p_status, :p_image, :p_description, :p_detail, :p_quantity, :p_params, :p_has_gift, :p_gift_title, :p_gift_image, :p_gift_description, :p_other_images, @p_product_id)');
    	$stmt->getResource()->bindParam('p_product_name', $arrParams['product_name']);
        $stmt->getResource()->bindParam('p_url', $arrParams['url']);
        $stmt->getResource()->bindParam('p_original_price', $arrParams['original_price']);
        $stmt->getResource()->bindParam('p_discount', $arrParams['discount']);
        $stmt->getResource()->bindParam('p_sale_price', $arrParams['sale_price']);
        $stmt->getResource()->bindParam('p_category_id', $arrParams['category_id']);
        $stmt->getResource()->bindParam('p_status', $arrParams['status']);
        $stmt->getResource()->bindParam('p_image', $arrParams['image']);
        $stmt->getResource()->bindParam('p_description', $arrParams['description']);
        $stmt->getResource()->bindParam('p_detail', $arrParams['detail']);
        $stmt->getResource()->bindParam('p_quantity', $arrParams['quantity']);
        $stmt->getResource()->bindParam('p_params', $arrParams['params']);
        $stmt->getResource()->bindParam('p_has_gift', $arrParams['has_gift']);
        $stmt->getResource()->bindParam('p_gift_title', $arrParams['gift_title']);
        $stmt->getResource()->bindParam('p_gift_image', $arrParams['gift_image']);
        $stmt->getResource()->bindParam('p_gift_description', $arrParams['gift_description']);
        $stmt->getResource()->bindParam('p_other_images', $arrParams['other_images']);
    	$stmt->execute();

        //close cursor
        $stmt->getResource()->closeCursor();
        unset($stmt);

        //get total
        $stmtOut = $this->_adapter->createStatement();
        $stmtOut->prepare('SELECT @p_product_id AS product_id');
        $result = $stmtOut->execute();
        $output = $result->current();
        $result = $output['product_id'] > 0 ? intval($output['product_id']) : false;

		return $result;
    }
}