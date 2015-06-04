<?php
namespace Model\User;

use Model\Db\Db;
use Model\Error\Error;
use Model\Log\Log;
use Model\Feedback;
class Get{
	public static function getUcoinHistory($UserID){
		$db = Db::getInstance();
		
		$sql = 'select a.OrderID, a.CreateDateTime , a.UcoinUsedPrice, a.UcoinGainPrice
				from tb_Order a
				where 1#a.UserID = ? and (a.UcoinUsedPrice > 0 or a.UcoinGainPrice > 0)
				order by a.CreateDateTime desc
				';
		$std=$db->prepare($sql);
		$std->bindValue(1, $UserID);
		$std->execute();
		$list=array();
		while(($row=$std->fetch())!==false){
			$list[]=$row;
		}
		
		
		return $list;
	}
	public static function getUserTotalCount(){
		
		$db = Db::getInstance();
		$sql = 'select count(*)
				from tb_User a
				where 1';
		$std=$db->prepare($sql);
		$bound=array();
		if(!$std->execute($bound)){
			Log::error($std->errorInfo());
			return false;
		}
		$row=$std->fetchColumn();
		return $row;
	}
	public static function getUserTotalCountByCommunityIDs($CommunityIDs){
		
		$db = Db::getInstance();
// 		$Communitys=
		
		$sql = 'select count(distinct a.UserID)
				from tb_UserCommunity a
				left join tb_User b on a.UserID = b.UserID
				where a.CommunityID in ('.implode(', ', $CommunityIDs).') and b.UserID is not null';
		$std=$db->prepare($sql);
		$bound=array();
		if(!$std->execute($bound)){
			Log::error($std->errorInfo());
			return false;
		}
		$row=$std->fetchColumn();
		return $row;
	}
	public function getStaffIdByCode($code){
		if(empty($code)){
			return false;
		}
		
		$db = Db::getInstance();
		$sql = 'select staffId from staff where code = ?';
		$std=$db->prepare($sql);
		$bound=array($code);
		if(!$std->execute($bound)){
			Log::error($std->errorInfo());
			return false;
		}else{
			if($std->fetchColumn()){
			return true;
			}else{
				return false;
			}
		}
	}
	
	public static function getUserIDFromAccount($account){
		$db = Db::getInstance();
		$sql = 'select UserID
				from tb_User
				where Account = ?';
		$std=$db->prepare($sql);
		$bound=array($account);
		if(!$std->execute($bound)){
			Log::error($std->errorInfo());
			Error::error('使用者帳號錯誤');
			return false;
		}
		
		return $std->fetchColumn();
	}
	
	public static function isSignedByDate($staffId, $date){
		$db = Db::getInstance();
		$sql = 'select count(*) 
				from signin a
				where a.staffId = :staffId and a.signin >= :dateStart and a.signin <= :dateEnd
				';
		
		$std=$db->prepare($sql);
		$dateStart = $date.' 00:00:00';
		$dateEnd = $date.' 23:59:59';
		$std->bindValue('staffId', $staffId, \PDO::PARAM_INT);
		$std->bindValue('dateStart', $dateStart, \PDO::PARAM_STR);
		$std->bindValue('dateEnd', $dateEnd, \PDO::PARAM_STR);
		if(!$std->execute()){
			Log::error($std->errorInfo());
			Error::error('打卡記錄抓取失敗');
			return false;
		}
		$count=$std->fetchColumn();
		return $count;
	}
	
	public static function getDataFromUserAccount($account){
		$db = Db::getInstance();
		$sql = 'select * 
				from staff 
				where code = ?';
		$std=$db->prepare($sql);
		$bound=array($account);
		if(!$std->execute($bound)){
			Log::error($std->errorInfo());
			Error::error('使用者帳號錯誤');
			return false;
		}
		
		return $std->fetch();
		
	}
	
	public function getUserCommunities($UserID){
		$db = Db::getInstance();
		$sql = 'select b.QuickNo, a.Address, b.CommunityID
				from tb_UserCommunity a
				left join tb_Community b on a.CommunityID = b.CommunityID
				where a.UserID = ?
				order by b.TypeID asc
				';
		$std=$db->prepare($sql);
		$bound=array($UserID);
		$list=array();
		if(!$std->execute($bound)){
				
			Log::error($std->errorInfo());
			Error::error('使用者ID錯誤');
			return false;
		}
		while(($row=$std->fetch())!==false){
			$list[]=$row;
		}
		return $list;
	}
	
	public function getUserAddress($UserID){
		$db = Db::getInstance();
		$sql = 'select b.QuickNo, a.Address, b.CommunityID
				from tb_UserCommunity a
				left join tb_Community b on a.CommunityID = b.CommunityID
				where a.Kind = 0 and a.UserID = ?';
		$std=$db->prepare($sql);
		$bound=array($UserID);
		$list=array();
		if(!$std->execute($bound)){
			
			Log::error($std->errorInfo());
			Error::error('使用者ID錯誤');
			return false;
		}
		while(($row=$std->fetch())!==false){
			$list[]=$row;
		}
		return $list;
	}
	
	public static function getDataFromUserID($UserID){
		$db = Db::getInstance();
		$sql = 'select *
				from tb_User
				where UserID = ?';
		$std=$db->prepare($sql);
		$bound=array($UserID);
		if(!$std->execute($bound)){
			Log::error($std->errorInfo());
			Error::error('使用者ID錯誤: ID = '.$UserID);
			Feedback::feedback(false);
			return false;
		}
		
		return $std->fetch();
	}
	
}