<?php
namespace Model\Wish;

use Model\Db\Db;
use Model\Error\Error;
class Set{
	public static function addWish($data){
		$db = Db::getInstance();
		
		$requireFields=array('deviceId', 'content');
		if(!$db->checkRequireFields($requireFields, $data)){
			Error::warning('欄位不得為空');
			return false;
		}
		
		$sql = 'select count(*) from wish where deviceId = :deviceId';
		$std=$db->prepare($sql);
		$std->bindValue('deviceId', $data['deviceId']);
		if(!$std->execute()){
			Error::warning('數量統計錯誤');
			return false;
		}
		
		$deviceWishCount = $std->fetchColumn();
		if($deviceWishCount >= 3){
			Error::warning('此裝置已許下三個願望');
			return false;
		}
		
		
		$sql = 'insert into wish (`deviceId`, `content`, `addDate`) values (:deviceId, :content, :addDate)';
		$std=$db->prepare($sql);
		$std->bindValue('deviceId', $data['deviceId']);
		$std->bindValue('content', $data['content']);
		$std->bindValue('addDate', date('Y-m-d H:i:s'));
		if(!$std->execute()){
			Error::warning($std->errorInfo());
			return false;
		}
		return true;
	}
	
	
	
	public static function vote($deviceId, $wId){
		$db = Db::getInstance();
		if(empty($deviceId) || empty($wId)){
			Error::warning('資料不得為空');
			return false;
		}
		
		$sql = 'select count(*) from wish_vote where deviceId = :deviceId';
		$std=$db->prepare($sql);
		$std->bindValue('deviceId', $deviceId);
		if(!$std->execute()){
			Error::warning('數量統計錯誤');
			return false;
		}
		
		$deviceWishCount = $std->fetchColumn();
		if($deviceWishCount >= 3){
			Error::warning('此裝置已投下三票');
			return false;
		}
		
		$sql = 'insert into wish_vote (`wId`, `deviceId`, `addDate`) values (:wId, :deviceId, :addDate)';
		$std=$db->prepare($sql);
		$std->bindValue('wId', $wId);
		$std->bindValue('deviceId', $deviceId);
		$std->bindValue('addDate', date('Y-m-d H:i:s'));
		
		if(!$std->execute()){
			Error::warning($std->errorInfo());
			return false;
			
		}
		
		$sql = 'update wish set voteCount = voteCount + 1 where wId = :wId';
		$std=$db->prepare($sql);
		$std->bindValue('wId', $wId);
		if(!$std->execute()){
			Error::warning($std->errorInfo());
			return false;
				
		}
		
		return true;
	}
}