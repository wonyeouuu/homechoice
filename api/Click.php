<?php

namespace Control;

use Model\Feedback;
use Model\Db\Db;

class Click {
	public function addrecord() {
		$code = $_REQUEST['code'];
		
		if(!$_SESSION['email']){
			Feedback::feedback(false);
		}
		
		$db = Db::getInstance();
		
		$sql = 'insert into click_record (`addDate`, `addTime`, `code`, `email`) values (NOW(), NOW(), :code, :email)';
		$std=$db->prepare($sql);
		$std->bindValue('code', $code);
		$std->bindValue('email', $_SESSION['email']);
		Feedback::feedback($std->execute());
		
		
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