<?php

namespace Web;

use Routine\Db;
use Routine\FileField;

class Product {
	public static $classfyList = null;
	public static function getClassfyProductList($class) {
		if (! is_null ( self::$classfyList )) {
			if (isset ( self::$classfyList [$class] )) {
				return self::$classfyList [$class];
			}
			return array ();
		}
		$db = Db::getInstance ();
		$sql = 'select a.*
				from av_product a
				where 1
				order by a.online_date desc';
		$std = $db->prepare ( $sql );
		$list = array ();
		if ($std->execute ()) {
			while ( ( $row = $std->fetch () ) !== false ) {
				if ($row ['classfy1'] == '1') {
					$list ['1'] [] = $row;
				}
				if ($row ['classfy2'] == '1') {
					$list ['2'] [] = $row;
				}
				if ($row ['classfy3'] == '1') {
					$list ['3'] [] = $row;
				}
			}
		}
		self::$classfyList = $list;
		// print_r(self::$classfyList);exit;
		return self::$classfyList [$class];
	}
	public static function getAllProductList() {
		$db = Db::getInstance ();
		$sql = 'select a.*, b.name catagories_name, c.name brand_name
				from av_product a
				left join av_product_catagories b on a.catagories_id = b.catagories_id
				left join av_product_brand c on a.brand_id = c.brand_id
				where 1
				order by a.online_date desc';
		$std = $db->prepare ( $sql );
		$list = array ();
		if ($std->execute ()) {
			while ( ( $row = $std->fetch () ) !== false ) {
				$list [] = $row;
			}
		}
		return $list;
	}
	public static function getCatagoriesInfor($catagories_id) {
		$db = Db::getInstance ();
		
		$sql = 'select a.*
				from av_product_catagories a
				where catagories_id = ?';
		$row = $db->querySingle ( $sql, array (
				$catagories_id 
		) );
		return $row;
	}
	public static function getBrandInfor($brand_id) {
		$db = Db::getInstance ();
		
		$sql = 'select a.*
				from av_product_brand a
				where brand_id = ?';
		$row = $db->querySingle ( $sql, array (
				$brand_id 
		) );
		return $row;
	}
	public static function getFirstColor($product_id) {
		$db = Db::getInstance ();
		$sql = 'select colortable_id from av_product_color where product_id = ?';
		$colortable_id = $db->querySingleColumn ( $sql, array (
				$product_id 
		) );
		return $colortable_id;
	}
	public static function getCatagoriesList() {
		$db = Db::getInstance ();
		$sql = 'select a.* from av_product_catagories a
				where a.status = 1
				order by sort_number asc';
		$std = $db->prepare ( $sql );
		$std->execute ();
		$cList = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$row ['image_url'] = \Routine\FileField::getImageRel ( $row ['image'], 'catagories' );
			$cList [] = $row;
		}
		return $cList;
	}
	public static function getBrandList() {
		$db = Db::getInstance ();
		$sql = 'select a.* from av_product_brand a order by sort_number asc';
		$std = $db->prepare ( $sql );
		$std->execute ();
		$cList = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$row ['image_url'] = \Routine\FileField::getImageRel ( $row ['image'], 'brand' );
			$cList [] = $row;
		}
		return $cList;
	}
	public static function getProductInfor($product_id) {
		$db = Db::getInstance ();
		
		$sql = 'select a.*, b.name brand_name 
				from av_product a
				left join av_product_brand b on a.brand_id = b.brand_id
				where product_id = ?';
		$row = $db->querySingle ( $sql, array (
				$product_id 
		) );
		return $row;
	}
	public static function getRealPrice($row) {
		$row ['price'] = ( int ) $row ['price'];
		if ($row ['discount'] == 0) {
			return $row ['price'];
		}
		return $row ['price'] * ( 1 - $row ['discount'] / 100 );
	}
	public static function getProductColors($product_id) {
		$db = Db::getInstance ();
		
		$sql = 'select a.colortable_id, b.image, c.name, c.code, c.image color_image, a.stock
				from av_product_color a
				inner join av_colortable c on a.colortable_id = c.colortable_id
				left join av_product_image b on a.product_id = b.product_id and a.colortable_id = b.colortable_id
				where a.product_id = ?
				order by a.sort_number, b.sort_number';
		$std = $db->prepare ( $sql );
		$bound = array (
				$product_id 
		);
		$list = array ();
		if ($std->execute ( $bound )) {
			while ( ( $row = $std->fetch () ) !== false ) {
				if (! isset ( $list [$row ['colortable_id']] )) {
					if (( FileField::getImageRel ( $row ['color_image'], 'colortable' ) ) != '') {
						$row ['css'] = 'url(' . FileField::getImageRel ( $row ['color_image'], 'colortable' ) . ')';
					} else {
						
						$row ['css'] = $row ['code'];
					}
					
					$list [$row ['colortable_id']] = array (
							'colortable_id' => $row ['colortable_id'],
							'name' => $row ['name'],
							'code' => $row ['code'],
							'image' => $row ['color_image'],
							'stock' => $row ['stock'],
							'css' => $row ['css'],
							'sub' => array () 
					);
				}
				
				if (! empty ( $row ['image'] )) {
					$list [$row ['colortable_id']] ['sub'] [] = array (
							'image' => $row ['image'],
							'image_url' => \Routine\FileField::getImageRel ( $row ['image'], 'product_color', array (
									'product_id' => $product_id,
									'colortable_id' => $row ['colortable_id'] 
							) ) 
					);
				}
			}
		}
		return $list;
	}
	public static function getProductAccessoryMaxSort($product_id) {
		$db = Db::getInstance ();
		$sql = 'select MAX(sort_number) from av_product_accessory where product_id = ?';
		$sort_number = $db->querySingleColumn ( $sql, array (
				$product_id 
		) );
		return $sort_number;
	}
	public static function getProductRelativeMaxSort($product_id) {
		$db = Db::getInstance ();
		$sql = 'select MAX(sort_number) from av_product_relative where product_id = ?';
		$sort_number = $db->querySingleColumn ( $sql, array (
				$product_id 
		) );
		return $sort_number;
	}
	public static function getAccessoryInfor($accessory_id) {
		$db = Db::getInstance ();
		$sql = 'select * from av_accessory where accessory_id = ?';
		$inf = $db->querySingle ( $sql, array (
				$accessory_id 
		) );
		$inf ['image_url'] = \Routine\FileField::getImageRel ( $inf ['image'], 'accessory' );
		return $inf;
	}
	public static function getProductAccessoryInfor($product_id, $accessory_id) {
		$db = Db::getInstance ();
		$sql = 'select * from av_product_accessory a 
				left join  av_accessory b on a.accessory_id = b.accessory_id
				where a.product_id = ? and a.accessory_id = ?';
		$inf = $db->querySingle ( $sql, array (
				$product_id,
				$accessory_id 
		) );
		$inf ['image_url'] = \Routine\FileField::getImageRel ( $inf ['image'], 'accessory' );
		return $inf;
	}
	public static function getNotProductAccessoryList($product_id) {
		$db = Db::getInstance ();
		$sql = 'select a.name, a.price, a.image, a.short_desc, a.accessory_id
				from av_accessory a
				left join av_product_accessory b on b.product_id = ? and a.accessory_id = b.accessory_id
				where b.product_id is null
				';
		
		$std = $db->prepare ( $sql );
		$bound = array (
				$product_id 
		);
		$list = array ();
		if ($std->execute ( $bound )) {
			while ( ( $row = $std->fetch () ) !== false ) {
				if (! empty ( $row ['image'] )) {
					if (is_file ( MediaAbs . '/images/accessory/' . $row ['image'] )) {
						$row ['image_url'] = MediaUrl . '/images/accessory/' . $row ['image'];
					}
				}
				$list [] = $row;
			}
		}
		return $list;
	}
	public static function getProductRelativeList($product_id) {
		$db = Db::getInstance ();
		$sql = 'select b.name, b.price, b.short_desc, a.relative_id, b.product_id, b.image
				from av_product_relative a
				left join av_product b on a.relative_id = b.product_id
				where a.product_id = ?
				order by a.sort_number asc
				';
		
		$std = $db->prepare ( $sql );
		$bound = array (
				$product_id 
		);
		$list = array ();
		if ($std->execute ( $bound )) {
			while ( ( $row = $std->fetch () ) !== false ) {
				$list [] = $row;
			}
		}
		return $list;
	}
	public static function getProductAccessoryList($product_id) {
		$db = Db::getInstance ();
		$sql = 'select b.name, b.price, b.image, b.short_desc, a.accessory_id
				from av_product_accessory a
				left join av_accessory b on a.accessory_id = b.accessory_id
				where a.product_id = ?
				order by a.sort_number asc
				';
		
		$std = $db->prepare ( $sql );
		$bound = array (
				$product_id 
		);
		$list = array ();
		if ($std->execute ( $bound )) {
			while ( ( $row = $std->fetch () ) !== false ) {
				if (! empty ( $row ['image'] )) {
					if (is_file ( MediaAbs . '/images/accessory/' . $row ['image'] )) {
						$row ['image_url'] = MediaUrl . '/images/accessory/' . $row ['image'];
					}
				}
				$list [] = $row;
			}
		}
		return $list;
	}
}