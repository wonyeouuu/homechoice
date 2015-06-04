<?php

namespace Model\User;

use Model\Db\Db;
use Model\Log\Log;
use Model\Error\Error;
use Model\Feedback;

class Set {
	
	public static function registerDevice($Account, $RegistrationID, $Platform){
		$db = Db::getInstance();
		$sql = 'update tb_User set RegistrationID = ?, Platform = ? where Account = ?';
		$std=$db->prepare($sql);
		$bound=array($RegistrationID, $Platform, $Account);
		if(!$std->execute($bound)){
			Log::log($std->errorInfo());
			Error::warning('無此帳號');
			
			return false;
		}
		return true;
	}
	public static function handleUserBalance($UserID, $Balance){
		$db = Db::getInstance();
		$Balance = (int)$Balance;
		if($Balance > 0){
			$operator = '+';
		}elseif($Balance < 0){
			$operator = '-';
		}else{
			return true;
		}
		$Balance = abs($Balance);
		$sql = 'update tb_User a
				set a.Balance = a.Balance '.$operator.' :Balance
				where a.UserID = :UserID';
		$std=$db->prepare($sql);
		$std->bindValue('Balance', $Balance, \PDO::PARAM_INT);
		$std->bindValue('UserID', $UserID, \PDO::PARAM_INT);
		if(!$std->execute()){
			return false;
		}
		return true;
	}
	public static function handleUserCoin($UserID, $Ucoin){
		$db = Db::getInstance();
		$Ucoin = (int)$Ucoin;
		if($Ucoin > 0){
			$operator = '+';
		}elseif($Ucoin < 0){
			$operator = '-';
		}else{
			return;
		}
		$Ucoin = abs($Ucoin);
		$sql = 'update tb_User a
				set a.Ucoin = a.Ucoin '.$operator.' :Ucoin
				where a.UserID = :UserID';
		$std=$db->prepare($sql);
		$std->bindValue('Ucoin', $Ucoin, \PDO::PARAM_INT);
		$std->bindValue('UserID', $UserID, \PDO::PARAM_INT);
		if(!$std->execute()){
			return false;
		}
		return true;
	}
	
	public static function signin($staffId, $datetime = null){
		$db=Db::getInstance();
		
		if($datetime == null){
			$datetime = date('Y-m-d H:i:s', time());
		}
		$sql = 'insert into signin (`staffId`, `signin`) values (:staffId, :signin)';
		$std=$db->prepare($sql);
		$std->bindValue('staffId', $staffId);
		$std->bindValue('signin', $datetime);
		if($std->execute()){
			return true;
		}else{
			return false;
		}
	}
	public static function signout($staffId, $datetime = null){
		$db=Db::getInstance();
		
		if($datetime == null){
			$datetime = date('Y-m-d H:i:s', time());
		}
		$sql = 'select * 
				from signin 
				where staffId = :staffId
				order by id desc
				limit 1';
		$std=$db->prepare($sql);
		$std->bindValue('staffId', $staffId, \PDO::PARAM_INT);
		if(!$std->execute()){
			Error::warning ( __FUNCTION__.': signin record missed.' );
			return false;
		}
		$lastSigninRecord = $std->fetch();
		
		$lastSigninDate = $lastSigninRecord['signin'];
		$lastSigninDate = date('Y-m-d', strtotime($lastSigninDate));
		$today = date('Y-m-d', time());
		
		if($lastSigninDate == $today && empty($lastSigninRecord['signout'])){//今天
			$sql = 'update signin set signout = :signout where id = :id';
			$std=$db->prepare($sql);
			$std->bindValue('signout', $datetime, \PDO::PARAM_STR);
			$std->bindValue('id', $lastSigninRecord['id'], \PDO::PARAM_INT);
			if(!$std->execute()){
				Error::warning(__FUNCTION__.': signout record update failed.');
				return false;
			}else{
				return true;
			}
		}else{//不是今天
			$sql = 'insert into signin (`staffId`, `signout`) values (:staffId, :signout)';
			$std=$db->prepare($sql);
			$std->bindValue('staffId', $staffId);
			$std->bindValue('signout', $datetime);
			if($std->execute()){
				return true;
			}else{
				Error::warning(__FUNCTION__.': signout record add failed');
				return false;
			}
		}
		
		
		
	}
	
	public function updateAddressByQuickNo($UserID, $QuickNo, $Address) {
		$db = Db::getInstance ();
		$CommunityGet = new \Model\Community\Get ();
		$CommunityID = $CommunityGet->getCommunityIDByQuickNo ( $QuickNo );
		if (empty ( $CommunityID )) {
			Error::warning ( 'QuickNO 錯誤，找不到對應的ID' );
			Feedback::feedback ( false );
		}
		$sql = 'update tb_UserCommunity set `Address` = ? where UserID = ? and CommunityID = ?';
		$std = $db->prepare ( $sql );
		$bound = array (
				$Address,
				$UserID,
				$CommunityID 
		);
		if ($std->execute ( $bound )) {
			return true;
		} else {
			return false;
		}
	}
	public function deleteCommunityByQuickNo($UserID, $QuickNo) {
		$db = Db::getInstance ();
		$CommunityGet = new \Model\Community\Get ();
		$CommunityID = $CommunityGet->getCommunityIDByQuickNo ( $QuickNo );
		if (empty ( $CommunityID )) {
			Error::warning ( 'QuickNO 錯誤，找不到對應的ID' );
			Feedback::feedback ( false );
		}
		$sql = 'delete from tb_UserCommunity where `UserID` = ? and `CommunityID` = ?';
		$std = $db->prepare ( $sql );
		$bound = array (
				$UserID,
				$CommunityID 
		);
		if ($std->execute ( $bound )) {
			return true;
		} else {
			return false;
		}
	}
	public function addAddress($UserID, $QuickNo, $Address) {
		$db = Db::getInstance ();
		$CommunityGet = new \Model\Community\Get ();
		list ( $CommunityID, $Kind ) = $CommunityGet->getCommunityIDByQuickNo ( $QuickNo, true );
		if (empty ( $CommunityID )) {
			Error::warning ( 'QuickNO 錯誤，找不到對應的ID' );
			Feedback::feedback ( false );
		}
		$sql = 'insert ignore into tb_UserCommunity (`UserID`, `Kind`, `Address`, `CommunityID`) values (?, ?, ?, ?)';
		$std = $db->prepare ( $sql );
		$bound = array (
				$UserID,
				$Kind,
				$Address,
				$CommunityID 
		);
		if ($std->execute ( $bound )) {
			return true;
		} else {
			return false;
		}
	}
	public function addCommunity($UserID, $QuickNo, $skipError = false) {
		$db = Db::getInstance ();
		$CommunityGet = new \Model\Community\Get ();
// 		list ( $CommunityID, $Kind ) = $CommunityGet->getCommunityIDByQuickNo ( $QuickNo, true );
		$cInfor = $CommunityGet->getInforByQuickNo($QuickNo);
		$CommunityID = $cInfor['CommunityID'];
		$Kind = $cInfor['Kind'];
		if (empty ( $CommunityID ) && $skipError !== true) {
			Error::warning ( 'QuickNO 錯誤，找不到對應的ID' );
			Feedback::feedback ( false );
		}
		if($cInfor['Kind']>1){
			$Status = '2';
		}
		
		$sql = 'insert ignore into tb_UserCommunity (`UserID`, `Kind`, `CommunityID`, `Status`) values (?, ?, ?, ?)';
		$std = $db->prepare ( $sql );
		$bound = array (
				$UserID,
				$Kind,
				$CommunityID ,
				$Status
		);
		if ($std->execute ( $bound )) {
			return true;
		} else {
			return false;
		}
	}
	public function update($row) {
		$db = Db::getInstance ();
		$UserID = $row ['UserID'];
		$requiredFields = array (
				'UserID',
				'Name',
				'Pwd',
				'TokenId',
				'Phone',
				'Email',
				'City',
				'District' 
		);
		
		$fields = array (
				'Name',
				'Pwd',
				'TokenId',
				'Phone',
				'Email',
				'City',
				'District',
				'Remark',
				'Kind',
				'Recommended',
				'CreateUserCode',
				'Gender' 
		);
		$realFields = array ();
		foreach ( $fields as $field ) {
			if (isset ( $row [$field] )) {
				$realFields [] = $field;
			}
		}
		Log::log ( $realFields );
		if (count ( $realFields ) > 0) {
			$sql = 'update tb_User set ' . $db->getUpdateColon ( $realFields ) . ' where UserID = :UserID';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $realFields, $row );
			$bound ['UserID'] = $UserID;
			if (! $std->execute ( $bound )) {
				Log::error ( $std->errorInfo () );
				Error::warning ( Excp_SysError );
				// Feedback::feedback(false);
				return false;
			}
		}
		
		$CommunityGet = new \Model\Community\Get ();
		
		$associationList = $CommunityGet->getAssociationListByUserID ( $UserID ); // 這個User參加的社團
		$checkTypes = array (
				'Address' => array (
						'code' => '0' 
				),
				'Community' => array (
						'code' => '1' 
				) 
		// ,
		// 'Societies' => array (
		// 'code' => '2'
		// )
				);
		// echo '<pre>';
		$associationKindList = array ();
		// print_r ( $checkTypes );
		foreach ( $checkTypes as $KindName => $KindRow ) {
			$associationKindList [$KindName] = array ();
		}
		
		// assoRow: 原本有的communities陣列
		// 判斷assoRow的TypeID，如果是2就是村里，要歸到Address
		foreach ( $associationList as $assoRow ) {
			if ($assoRow ['TypeID'] == '2') {
				$associationKindList ['Address'] [$assoRow ['QuickNo']] = $assoRow;
			} else {
				$associationKindList ['Community'] [$assoRow ['QuickNo']] = $assoRow;
			}
		}
		$deleteList = array ();
		$replaceList = array ();
		// print_r ( $associationKindList );
		// exit;
		
		// print_r ( $checkTypes );
		$findCommunityIDList = array (); // 用來存communityid=null的quickno(新增的資料)
		foreach ( $checkTypes as $KindName => $KindRow ) {
			$rowQuickNos = array (); // post過來的資料的QuickNO array
			if (! isset ( $row [$KindName] )) {
				continue;
			}
			foreach ( ( array ) $row [$KindName] as $subRow ) {
				// 傳過來的所有資料的QuickNo，用來跟資料庫已存的User的社團QuickNo陣列($associationKindList)比對
				// 如果沒有的話放進deleteList
				$rowQuickNos [] = $subRow ['QuickNo'];
				
				// 傳過來的所有資料全都要replace into進DB，之後要檢查replaceList[array]，如果communityid是null，代表新增要再撈一次
				$replaceList [] = array (
						$UserID,
						$KindRow ['code'],
						$subRow ['Name'],
						$associationKindList [$KindName] [$subRow ['QuickNo']] ['CommunityID'] 
				);
				if (empty ( $associationKindList [$KindName] [$subRow ['QuickNo']] ['CommunityID'] )) {
					$key = max ( array_keys ( $replaceList ) );
					$findCommunityIDList [$key] = $subRow ['QuickNo'];
				}
			}
			foreach ( $associationKindList [$KindName] as $QuickNo => $assoRow ) {
				if (! in_array ( $QuickNo, $rowQuickNos )) {
					$deleteList [] = $assoRow ['CommunityID'];
				}
			}
		}
		
		$CommunityQuickNoMappings = array ();
		if (count ( $findCommunityIDList ) > 0) {
			$sql = 'select CommunityID, QuickNo, Kind from tb_Community where QuickNo in (' . $db->getQuestionMarkByArray ( $findCommunityIDList ) . ')';
			$std = $db->prepare ( $sql );
			$bound = array_values ( $findCommunityIDList );
			if (! $std->execute ( $bound )) {
				Error::warning ( 'CommunityID對應錯誤' );
			} else {
				while ( ( $row = $std->fetch () ) !== false ) {
					$CommunityQuickNoMappings [$row ['QuickNo']] = $row ['CommunityID'];
					$key = array_search ( $row ['QuickNo'], $findCommunityIDList );
					if (isset ( $replaceList [$key] )) {
						$replaceList [$key] [3] = $row ['CommunityID'];
						//如果是允許或秘密(Kind = 2, 3), 加入會員要等待中
						if ($row ['Kind'] > 1) {
							$replaceList [$key] [4] = '2';
						} else {
							$replaceList [$key] [4] = '1';
						}
					} else {
					}
				}
			}
		}
		
		// foreach ( $replaceList as $key => $value ) {
		
		// }
		// exit;
		if (count ( $deleteList ) > 0) {
			$sql = 'delete from tb_UserCommunity where UserID = ? and CommunityID in (' . $db->getQuestionMarkByArray ( $deleteList ) . ')';
			$std = $db->prepare ( $sql );
			$bound = array (
					$UserID 
			);
			$bound = array_merge ( $bound, $deleteList );
			if(!$std->execute ( $bound )){
				Error::warning('刪除社群失敗');
				return false;
			}
		}
		
		if (count ( $replaceList ) > 0) {
			// print_r ( $replaceList );
			$piece = '(?, ?, ?, ?, ?)';
			$sql = 'replace into tb_UserCommunity (`UserID`, `Kind`, `Address`, `CommunityID`, `Status`) values ' . implode ( ', ', array_fill ( 0, count ( $replaceList ), $piece ) );
			$std = $db->prepare ( $sql );
			$bound = array ();
			foreach ( $replaceList as $replaceListRow ) {
				$bound = array_merge ( $bound, $replaceListRow );
			}
// 			print_r($bound);
			if(!$std->execute ( $bound )){
				Error::warning('新增社群失敗');
				return false;
			}
		}
		return true;
		exit ();
		
	}
	public function insertUser($row) {
		$db = Db::getInstance ();
		$requiredFields = array (
				'Account',
				'Name',
				'Pwd' 
		);
		foreach ( $requiredFields as $requiredField ) {
			if (empty ( $requiredField )) {
				Error::warning ( '必填欄位有誤' );
				return false;
			}
		}
		
		$fields = array (
				'Account',
				'Name',
				'Pwd',
				'TokenId',
				'Phone',
				'Email',
				'Remark',
				'Kind',
				'Recommended',
				'CreateUserCode',
				'Gender' 
		);
		$row ['Kind'] = 1;
		$row ['CreateUserCode'] = 'APP';
		$realFields = array ();
		foreach ( $fields as $field ) {
			if (isset ( $row [$field] )) {
				$realFields [] = $field;
			}
		}
		if (count ( $realFields ) > 0) {
			$sql = 'insert into tb_User (' . $db->getFieldsSeparateByDot ( $realFields ) . ', CreateDateTime) values (' . $db->getColonFieldsSeparateByDot ( $realFields ) . ', NOW())';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $realFields, $row );
			
			if (! $std->execute ( $bound )) {
				print_r ( $bound );
				echo $sql;
				Log::error ( $std->errorInfo () );
				Error::warning ( Excp_SysError );
				// Feedback::feedback(false);
				return false;
			}
		}
		$UserID = $db->lastInsertId ();
		
		if (isset ( $row ['QuickNo'] )) {
			$CommunityGet = new \Model\Community\Get ();
			$CommunityID = $CommunityGet->getCommunityIDByQuickNo ( $row ['QuickNo'] );
			
			$sql = 'insert into tb_UserCommunity (`UserID`, `CommunityID`, `Kind`, `Address`) values (?, ?, 0, ?)';
			$std = $db->prepare ( $sql );
			$bound = array (
					$UserID,
					$CommunityID,
					$row ['Address'] 
			);
			$std->execute ( $bound );
		}
		
		return $UserID;
	}
}