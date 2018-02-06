/*
Navicat MySQL Data Transfer

Source Server         : LOCAL
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : qthm

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2018-02-06 16:57:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `full_parent` longtext,
  `catename` longtext,
  `catecode` longtext,
  `image` longtext,
  `display_order` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `description` longtext,
  `creation_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `thumb` longtext,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hot_object
-- ----------------------------
DROP TABLE IF EXISTS `hot_object`;
CREATE TABLE `hot_object` (
  `hot_id` int(11) NOT NULL AUTO_INCREMENT,
  `showed_area` varchar(30) DEFAULT NULL COMMENT 'trangchu_top, trangchu_category, category_top, product_detail, promotion, business, event, search_page',
  `object_id` int(11) DEFAULT NULL,
  `object_type` int(11) DEFAULT NULL COMMENT '1: product;',
  `thumbnail_url` varchar(255) DEFAULT NULL COMMENT 'anh banner',
  `url` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1' COMMENT '1: normal (duyet); 0 delete, 2:inactive',
  `creation_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`hot_id`),
  KEY `idx_object` (`object_id`,`object_type`,`showed_area`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=263 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `original_price` int(11) DEFAULT NULL,
  `discount` int(11) DEFAULT NULL,
  `sale_price` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `creation_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text COMMENT 'Giới thiệu ngắn về sản phẩm',
  `detail` text COMMENT 'Thông tin chi tiết về sản phẩm',
  `quantity` int(11) NOT NULL DEFAULT '0' COMMENT 'Số lượng sản phẩm',
  `params` text COMMENT 'Các thuôc tính mở rộng của sản phẩm (trọng lượng, kích thước), lưu trữ dưới dạng json',
  `has_gift` tinyint(4) DEFAULT '0' COMMENT 'Set sản phẩm có quà tặng hay không',
  `gift_title` varchar(255) DEFAULT NULL COMMENT 'Tiêu đề quà tặng',
  `gift_image` text COMMENT 'ảnh quà tặng',
  `gift_description` text COMMENT 'mô tả quà tặng',
  `other_images` text COMMENT 'ảnh khác của sản phẩm, lưu trữ dạng json',
  PRIMARY KEY (`product_id`),
  KEY `idx_cate` (`category_id`) USING BTREE,
  KEY `idx_time` (`creation_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2042 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL COMMENT '1: active email; 2: chua active email',
  `creation_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `idx_emai` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4432 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Procedure structure for sp_addUsers
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_addUsers`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_addUsers`(p_user_name VARCHAR(255), p_email VARCHAR(100), p_fullname VARCHAR(100), p_password VARCHAR(100), p_status INT, OUT p_user_id INT)
BEGIN
  INSERT INTO users(user_name, email, fullname, `password`, `status`, creation_time, update_time)
  VALUES (p_user_name, p_email, p_fullname, p_password, p_status, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

  SET p_user_id = LAST_INSERT_ID();
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_be_getDetailCategory
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_be_getDetailCategory`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_be_getDetailCategory`(p_category_id INT)
BEGIN 
  SELECT * 
  FROM category 
  WHERE category_id = p_category_id;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_countProducts
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_countProducts`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_countProducts`(p_status INT)
BEGIN
  SELECT COUNT(*) AS total
  FROM products
  WHERE `status` = IFNULL(p_status,`status`)
  AND `status` <> 0;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_getDetailProduct
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_getDetailProduct`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getDetailProduct`(p_product_id TEXT)
BEGIN
  SET @stmt = CONCAT('
    SELECT p.*
    FROM products p
    WHERE p.product_id IN (',p_product_id,')
    AND p.`status` <> 0
    ORDER BY p.creation_time DESC
    ');

  PREPARE v_stmt FROM @stmt;
  EXECUTE v_stmt;
  DEALLOCATE PREPARE v_stmt;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_getDetailUser
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_getDetailUser`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getDetailUser`(p_user_id VARCHAR(255))
BEGIN
  SET @stmt = CONCAT('
  SELECT user_id, user_name, email, fullname, `password`, `status`, creation_time, update_time
  FROM users
  WHERE `status` <> 0
  AND user_id IN (',p_user_id,')');

  PREPARE v_stmt FROM @stmt;
  EXECUTE v_stmt;
  DEALLOCATE PREPARE v_stmt;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_getListCategory
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_getListCategory`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getListCategory`()
BEGIN
  SELECT c.*
  FROM category c
  WHERE c.`status` = 1
  ORDER BY parent_id, display_order;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_getListHotObject
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_getListHotObject`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getListHotObject`(p_showed_area VARCHAR(30), p_object_type INT, p_status INT, p_limit INT, p_offset INT, OUT p_total INT)
BEGIN
  SET @stmt = '
  SELECT SQL_CALC_FOUND_ROWS *
  FROM hot_object
  WHERE `status` <> 4';

  IF p_object_type IS NOT NULL THEN
    SET @stmt = CONCAT(@stmt, '
      AND object_type = ',p_object_type);
  END IF;

  IF p_showed_area IS NOT NULL THEN
    SET @stmt = CONCAT(@stmt,'
      AND showed_area = "',p_showed_area,'"');
  END IF;

  IF p_status IS NOT NULL THEN
    SET @stmt = CONCAT(@stmt, '
      AND `status` = ',p_status);
  ELSE
    SET @stmt = CONCAT(@stmt, '
      AND `status` <> 0 ');
  END IF;

  SET @stmt = CONCAT(@stmt, '
      ORDER BY display_order, creation_time DESC 
      LIMIT ',p_limit,' OFFSET ',p_offset);

  PREPARE v_stmt FROM @stmt;
  EXECUTE v_stmt;
  DEALLOCATE PREPARE v_stmt;

  SET p_total = FOUND_ROWS();

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_getListProducts
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_getListProducts`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getListProducts`(p_category_id VARCHAR(2000), p_limit INT, p_offset INT, OUT p_total INT)
BEGIN 

    SET @stmt = "
              SELECT SQL_CALC_FOUND_ROWS p.product_id, p.update_time
              FROM products p
              WHERE p.status = 1";

    IF p_category_id IS NOT NULL THEN 
      SET @stmt = CONCAT(@stmt, " AND  p.category_id IN (", p_category_id,")");
    END IF;

    SET @stmt = CONCAT(@stmt, " ORDER BY p.update_time DESC LIMIT ",p_limit, " OFFSET ", p_offset);

    PREPARE v_stmt FROM @stmt;
    EXECUTE v_stmt;
    DEALLOCATE PREPARE v_stmt;

    SET p_total = FOUND_ROWS();
        
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_getListProducts_v2
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_getListProducts_v2`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getListProducts_v2`(p_category_id VARCHAR(2000), p_order_by VARCHAR(255), p_column_output VARCHAR(255),p_limit INT, p_offset INT, OUT p_total INT)
BEGIN 
  DECLARE v_order_by TEXT DEFAULT '';

    IF p_column_output = 'price_discount' THEN
      SET @stmt = "
      SELECT SQL_CALC_FOUND_ROWS p.product_id, (p.original_price - p.sale_price) score, p.original_price, p.sale_price, p.discount, p.publish_time ";
      SET v_order_by = CONCAT(" score ", p_order_by, ", p.discount DESC, p.publish_time ASC");

    ELSEIF p_column_output = 'sale_price' THEN
      SET @stmt = "
      SELECT SQL_CALC_FOUND_ROWS p.product_id, sale_price score, p.original_price, p.sale_price, p.discount, p.publish_time";
      SET v_order_by = CONCAT(" score ", p_order_by, ", p.publish_time ASC");

    ELSEIF p_column_output = 'percent_discount' THEN
      SET @stmt = "
      SELECT SQL_CALC_FOUND_ROWS p.product_id, p.discount score, p.original_price, p.sale_price, p.discount, p.publish_time";
      SET v_order_by = CONCAT(" score ", p_order_by, ", (p.original_price - p.sale_price) DESC, p.publish_time ASC");
    END IF;
    
    SET @stmt = CONCAT(@stmt,"
      FROM products p
      WHERE p.status = 1");

    IF p_category_id IS NOT NULL THEN 
      SET @stmt = CONCAT(@stmt, " AND  p.category_id IN (", p_category_id,")");
    END IF;

    SET @stmt = CONCAT(@stmt, " ORDER BY ", v_order_by, " LIMIT ",p_limit, " OFFSET ", p_offset);

    PREPARE v_stmt FROM @stmt;
    EXECUTE v_stmt;
    DEALLOCATE PREPARE v_stmt;

    SET p_total = FOUND_ROWS();
        
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_getListUser
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_getListUser`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getListUser`(p_user_name VARCHAR(255), p_email VARCHAR(100), p_status INT, p_limit INT, p_offset INT, OUT p_rowcount INT)
BEGIN
    
      SET @stmt = CONCAT("SELECT SQL_CALC_FOUND_ROWS a.*
                      FROM users a                      
                      WHERE 1=1");

  IF p_status IS NOT NULL THEN
    SET @stmt = CONCAT(@stmt, '
        AND status = ',p_status);
  ELSE
    SET @stmt = CONCAT(@stmt, '
        AND status <> 0');
  END IF;

  IF p_user_name IS NOT NULL THEN
    SET @stmt = CONCAT(@stmt, " AND a.user_name ='",p_user_name,"'");
  END IF;

  IF p_email IS NOT NULL THEN
    SET @stmt = CONCAT(@stmt, " AND a.email ='",p_email,"'");
  END IF;
  
  IF p_limit IS NOT NULL AND p_offset IS NOT NULL THEN
    SET @stmt = CONCAT(@stmt, "  LIMIT ",p_limit," OFFSET ",p_offset);
  END IF;

  PREPARE v_stmt FROM @stmt;
  EXECUTE v_stmt;
  DEALLOCATE PREPARE v_stmt;

  SET p_rowcount = FOUND_ROWS();

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_getUserByEmail
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_getUserByEmail`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getUserByEmail`(p_email VARCHAR(100))
BEGIN
  SELECT *
  FROM users 
  WHERE email = p_email;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_insertCategory
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_insertCategory`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertCategory`(p_image VARCHAR(255), p_parent_id INT, p_full_parent VARCHAR(255), p_catename VARCHAR(255), p_catecode VARCHAR(255), p_display_order INT, p_status TINYINT, p_description TEXT, p_thumb VARCHAR(255), OUT p_category_id INT)
BEGIN 
    INSERT INTO category(parent_id, full_parent, catename, catecode, image, display_order, status, description, creation_time, update_time, thumb)
    VALUES(p_parent_id, p_full_parent, p_catename, p_catecode, p_image, p_display_order, p_status, p_description, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), p_thumb);

    SET p_category_id = LAST_INSERT_ID();
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_insertHotObject
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_insertHotObject`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertHotObject`(p_showed_area VARCHAR(30), p_object_id INT, p_object_type INT, p_thumbnail_url VARCHAR(255), p_url VARCHAR(255), p_display_order INT, p_status INT)
BEGIN
  DECLARE vHotID INT;
  SELECT hot_id INTO vHotID FROM hot_object WHERE showed_area = p_showed_area AND object_id = p_object_id AND object_type = p_object_type AND `status` = 1;

  IF(vHotID IS NULL)THEN
    INSERT INTO hot_object(showed_area, object_id, object_type, thumbnail_url, url, display_order, `status`, creation_time, update_time)
    VALUES (p_showed_area, p_object_id, p_object_type, p_thumbnail_url, p_url, p_display_order, p_status, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());
    SELECT 1 AS result;
  ELSE
    SELECT -1 AS result;
  END IF;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_insertProduct
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_insertProduct`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insertProduct`(p_product_name VARCHAR(255), p_url VARCHAR(255), p_original_price INT, p_discount INT, p_sale_price INT, p_category_id INT, p_status INT, p_image VARCHAR(255), p_description TEXT, p_detail TEXT, p_quantity INT, p_params TEXT, p_has_gift INT, p_gift_title VARCHAR(255), p_gift_image TEXT, p_gift_description TEXT, p_other_images TEXT, OUT p_product_id INT)
BEGIN
  INSERT INTO products(product_name, url, original_price, discount, sale_price, category_id, `status`, 
    creation_time, update_time, image, description, detail, quantity, params, has_gift, gift_title, gift_image, gift_description, other_images)
  VALUES(p_product_name, p_url, p_original_price, p_discount, p_sale_price, p_category_id, p_status,
    UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), p_image, p_business_id, p_referer_promotion_id, p_market_price, p_description, p_detail, p_quantity, p_params, p_has_gift, p_gift_title, p_gift_image, p_gift_description, p_other_images);

  SET p_product_id = LAST_INSERT_ID();

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_updateCategory
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_updateCategory`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateCategory`(p_image VARCHAR(255), p_parent_id INT, p_full_parent VARCHAR(255), p_catename VARCHAR(255), p_catecode VARCHAR(255), p_display_order INT, p_status TINYINT, p_description TEXT, p_thumb VARCHAR(255), p_category_id INT)
BEGIN 
    UPDATE category
    SET parent_id =  IFNULL(p_parent_id, parent_id),
      full_parent = IFNULL(p_full_parent, full_parent) ,
      catename = IFNULL(p_catename, catename) ,
      catecode = IFNULL(p_catecode, catecode),
      display_order = IFNULL(p_display_order, display_order),
      status = IFNULL(p_status, status),
      description = IFNULL(p_description, description),
      update_time = UNIX_TIMESTAMP(),
      image = IFNULL(p_image, image),
      thumb = IFNULL(p_thumb, thumb)
    WHERE category_id = p_category_id;
       
    SELECT ROW_COUNT() as result;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_updateHotObject
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_updateHotObject`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateHotObject`(p_hot_id INT, p_showed_area VARCHAR(30), p_object_id INT, p_object_type INT, p_thumbnail_url VARCHAR(255), p_url VARCHAR(255), p_display_order INT, p_status INT)
BEGIN
  UPDATE hot_object
  SET showed_area = IFNULL(p_showed_area,showed_area),
    object_id = IFNULL(p_object_id,object_id),
    object_type = IFNULL(p_object_type,object_type),
    thumbnail_url = IFNULL(p_thumbnail_url,thumbnail_url),
    url = IFNULL(p_url,url),
    display_order = IFNULL(p_display_order,display_order),
    `status` = IFNULL(p_status,`status`),
    update_time = UNIX_TIMESTAMP()
  WHERE hot_id = p_hot_id;
END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_updateProduct
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_updateProduct`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateProduct`(p_product_id INT, p_product_name VARCHAR(255), p_url VARCHAR(255), p_original_price INT, p_discount INT, p_sale_price INT, p_category_id INT, p_status INT, p_image VARCHAR(255), p_description TEXT, p_detail TEXT, p_quantity INT, p_params TEXT, p_has_gift INT, p_gift_title varchar(255), p_gift_image TEXT, p_gift_description TEXT, p_other_images TEXT)
BEGIN
  UPDATE products
  SET product_name = IFNULL(p_product_name,product_name),
    url = IFNULL(p_url,url),
    original_price = IFNULL(p_original_price,original_price),
    discount = IFNULL(p_discount,discount),
    sale_price = IFNULL(p_sale_price,sale_price),
    category_id = IFNULL(p_category_id,category_id),
    `status` = IFNULL(p_status,`status`),
    update_time = UNIX_TIMESTAMP(),
    image = IFNULL(p_image,image),
    description = IFNULL(p_description,description),
    detail      = IFNULL(p_detail, detail),
    quantity    = IFNULL(p_quantity, quantity),
    params      = IFNULL(p_params, params),
    has_gift    = IFNULL(p_has_gift, has_gift),
    gift_title  = p_gift_title,
    gift_image  = p_gift_image,
    gift_description = p_gift_description,
    other_images= IFNULL(p_other_images, other_images)
  WHERE product_id = p_product_id;

  SELECT ROW_COUNT() AS result;

END
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for sp_updateUsers
-- ----------------------------
DROP PROCEDURE IF EXISTS `sp_updateUsers`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_updateUsers`(p_user_id INT, p_user_name VARCHAR(255), p_email VARCHAR(100), p_fullname VARCHAR(100), p_password VARCHAR(100), p_status INT)
BEGIN
  UPDATE users
  SET user_name = IFNULL(p_user_name,user_name),
    email = IFNULL(p_email,email),
    fullname = IFNULL(p_fullname,fullname),
    `password` = IFNULL(p_password,`password`),
    `status` = IFNULL(p_status,`status`),
    update_time = UNIX_TIMESTAMP()
  WHERE user_id = p_user_id;

  SELECT ROW_COUNT() AS result;
END
;;
DELIMITER ;
