<?php

namespace Control;

use Model\Feedback;

class Wish {
	public function addwish() {
		$result = \Model\Wish\Set::addWish($_POST) ;
		if(!$result){
			Feedback::feedback(false);
		}
		Feedback::feedback ( true );
	}
	
	public function getwishstatuslist(){
		if(!$list=\Model\Wish\Get::getstatuslist()){
			Feedback::feedback(false);
		}
		Feedback::feedback($list);
	}
	
	public function getwishlist(){
		if(!$list=\Model\Wish\Get::getlist()){
			Feedback::feedback(false);
		}
		Feedback::feedback($list);
	}
	
	public function vote(){
		$wId=$_POST['wId'];
		$deviceId = $_POST['deviceId'];
		
		\Model\Wish\Set::vote($deviceId, $wId);
		
		Feedback::feedback(true);
	}
}