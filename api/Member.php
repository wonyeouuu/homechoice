<?php

namespace Control;

use Model\Error\Error;
use Model\Feedback;
use Model\Log\Log;
// use \Model\Member as Member;
class Member {
	public function getmemberbyemail(){
		$email = $_REQUEST['email'];
		$data = \Model\Member\Data::getMemberByEmail($email);
		\Model\Member\Data::login($data);
		Feedback::feedback($data);
	}
	
	public function addmember(){
		$email = $_REQUEST['email'];
		$capacity = $_REQUEST['capacity'];
		$system = $_REQUEST['system'];
		\Model\Member\Data::login($_REQUEST);
		$result= \Model\Member\Data::addMember($email, $capacity,$system);
		Feedback::feedback($result);
	}
	
}