<?php

namespace Control;

use Model\Feedback;
use Model\Db\Db;
// use \Model\Member as Member;
class Update {
	public function getlastupdatetime() {
		$db = Db::getInstance ();
		
		$sql = 'select * from update_time';
		$std = $db->prepare ( $sql );
		$std->execute ();
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$list [$row ['type']] = strtotime ( $row ['updateTime'] );
		}
		Feedback::feedback ( $list );
	}
	public function updateall() {
		$cityId = isset ( $_REQUEST ['cityId'] ) ? $_REQUEST ['cityId'] : null;
		$indexBanner = \Model\Banner\Get::getList ();
		$indexMerchant = \Model\Merchant\Get::getIndexList ( $cityId );
// 		$indexCoupon = \Model\Coupon\Get::getIndexList ( $cityId );
		$indexCoupon = array();
		$indexList=array_merge($indexMerchant, $indexCoupon);
		$merchantCategory = \Model\Merchant\Get::getCategoryList ();
		$couponCategory = \Model\Coupon\Get::getCategoryList ();
		$merchantList = \Model\Merchant\Get::getList ( $cityId );
		$couponList = \Model\Coupon\Get::getList ( $cityId );
		
		$return = array (
				'indexBanner' => $indexBanner,
				'indexList'=>$indexList,
				'merchantCategory' => $merchantCategory,
				'couponCategory' => $couponCategory,
				'merchantList' => $merchantList ,
				'couponList' => $couponList ,
		);
		Feedback::feedback ( $return );
	}
}