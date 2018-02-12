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

class User
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

    public function addUser($arrParams)
    {
    	$result = false;
    	$stmt = $this->_adapter->createStatement();
    	$stmt->prepare('CALL sp_addUsers(:p_user_name, :p_email, :p_fullname, :p_password, :p_status, @p_user_id)');
    	$stmt->getResource()->bindParam('p_user_name', $arrParams['user_name']);
    	$stmt->getResource()->bindParam('p_email', $arrParams['email']);
    	$stmt->getResource()->bindParam('p_fullname', $arrParams['fullname']);
    	$stmt->getResource()->bindParam('p_password', $arrParams['password']);
    	$stmt->getResource()->bindParam('p_status', $arrParams['status']);
    	$stmt->execute();

        //close cursor
        $stmt->getResource()->closeCursor();
        unset($stmt);

        //get total
        $stmtOut = $this->_adapter->createStatement();
        $stmtOut->prepare('SELECT @p_user_id AS user_id');
        $result = $stmtOut->execute();
        $output = $result->current();
        $result = $output['user_id'] > 0 ? intval($output['user_id']) : false;

		return $result;
    }

    public function getDetailUser($user_id)
    {
    	$arrReturn = array();
    	$stmt = $this->_adapter->createStatement();
    	$stmt->prepare('CALL sp_getDetailUser(:p_user_id)');
    	$stmt->getResource()->bindParam('p_user_id', $user_id);
    	$stmt->execute();
    	while($row = $stmt->getResource()->fetch(PDO::FETCH_ASSOC))
		{
			$arrReturn = $row;
		}

        //close cursor
        $stmt->getResource()->closeCursor();
        unset($stmt);

		return $arrReturn;
    }

    public function getDetailUserByEmail($email)
    {
        $arrReturn = array();
        $stmt = $this->_adapter->createStatement();
        $stmt->prepare('CALL sp_getUserByEmail(:p_email)');
        $stmt->getResource()->bindParam('p_email', $email);
        $stmt->execute();
        while($row = $stmt->getResource()->fetch(PDO::FETCH_ASSOC))
        {
            $arrReturn = $row;
        }

        //close cursor
        $stmt->getResource()->closeCursor();
        unset($stmt);

        return $arrReturn;
    }
}