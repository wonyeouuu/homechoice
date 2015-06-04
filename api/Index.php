<?php

namespace Control;

use Model\Feedback;
class Index{
	public function getbannerlist(){
		$indexBanner = \Model\Banner\Get::getList ();
		Feedback::feedback($indexBanner);
	}
	public function getindexlist(){
		$cityId = isset ( $_REQUEST ['cityId'] ) ? $_REQUEST ['cityId'] : null;
		$indexMerchant = \Model\Merchant\Get::getIndexList ( $cityId );
		$indexList=$indexMerchant;
		
		Feedback::feedback($indexList);
	}
}