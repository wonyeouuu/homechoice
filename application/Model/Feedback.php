<?php

namespace Model;

use Model\Error\Error;
use Model\User\Security;

class Feedback {
	public static $returnData;
	public static function feedback($success, $returnError = true, $exit = true) {
		$return = array ();
		if (! empty ( self::$returnData )) {
			$return['return'] = self::$returnData;
			$return['success'] = 1;
		}
		
		$errMsg = Error::getFeedBackMsg ();
		if (is_bool ( $success )) {
			if ($success === true) {
				$return['success'] = 1;
			} elseif (! empty (  $errMsg)) {
				$return['success'] = 0;
			} else {
				$return['success'] = 0;
			}
		} elseif (is_int ( $success )) {
			$msg = 'HTTP/1.1 ' . $success . ' ';
			if (! empty ( $errMsg )) {
				$msg .= $errMsg;
			}
			header ( $msg, true, $success );
			exit ();
		} else {
			$return['return'] = $success;
			$return['success'] = 1;
		}
		
		if ($returnError && Error::getFeedBackMsg()!='') {
			
		$return ['errorMsg'] = Error::getFeedBackMsg ();
		}
		
		echo json_encode ( $return );
		if ($exit) {
			exit ();
		}
	}
	public static function checkLogined() {
		if (! Security::logined ()) {
			Error::warning ( '未登入' );
			self::feedback ( 404, true );
		}
	}
}