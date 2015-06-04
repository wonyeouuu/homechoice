<?php
namespace Model\User;

use Model\Db\Db;
class Security{
	
	public static function logined(){
		if(empty($_SESSION['staffId'])){
			return false;
		}
		return true;
	}
	
	
	public function isAccount($Account){
		$db = Db::getInstance();
		$sql = 'select count(*) 
				from tb_User 
				where Account = ?';
		$std=$db->prepare($sql);
		if($std->execute(array($Account))){
			$n = $std->fetchColumn();
			if($n > 0){
				return true;
			}
		}
		
		return false;
	}
	public function isUser($UserID){
		$db = Db::getInstance();
		$sql = 'select count(*) 
				from tb_User 
				where UserID = ?';
		$std=$db->prepare($sql);
		if($std->execute(array($UserID))){
			$n = $std->fetchColumn();
			if($n > 0){
				return true;
			}
		}
		
		return false;
	}
	
	public static function unregisterLoginSession(){
		unset($_SESSION['UserID']);
		unset($_SESSION['Name']);
		unset($_SESSION['TokenId']);
		unset($_SESSION['Kind']);
	}
	
	public static function registerLoginSession($data){
		//@session_start();
		$_SESSION['code'] = $data['code'];
		$_SESSION['staffId'] = $data['staffId'];
		$_SESSION['name'] = $data['name'];
	}
	
	
}