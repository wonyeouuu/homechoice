<?php

namespace Web;

use Routine\Db;

class Member {
	public static $countries = array (
			'1' => array (
					'id' => 1,
					'name' => '台灣',
					'code' => 'TW' 
			),
			'2' => array (
					'id' => 2,
					'name' => '美國',
					'code' => 'US' 
			),
			'3' => array (
					'id' => 3,
					'name' => '中國',
					'code' => 'CH' 
			) 
	);
	public static $levels = array (
			'1' => array (
					'id' => 1,
					'name' => '一般會員' ,
					'meet_take'=>false
			) ,
			'2' => array (
					'id' => 2,
					'name' => '黃金會員' ,
					'meet_take'=>true
			)
	);
	public static function getMemberInfor($member_id = null) {
		if (empty ( $member_id )) {
			$member_id = self::getMemberId ();
		}
		$db = \Routine\Db::getInstance ();
		$sql = 'select * from av_member a
				where a.member_id = ?';
		$bound = array (
				$member_id 
		);
		$std = $db->prepare ( $sql );
		if (! $std->execute ( $bound )) {
			return false;
		}
		$infor = $std->fetch ();
		$infor = self::collateMemberRow ( $infor );
		return $infor;
	}
	public static function getOrderList($member_id = null) {
		if ($member_id == null) {
			$member_id = self::getMemberId ();
		}
		$db = Db::getInstance ();
		$sql = 'select * 
				from av_order a
				where a.member_id = ?
				order by a.order_id desc';
		$std = $db->prepare ( $sql );
		$bound = array (
				$member_id 
		);
		$list = array ();
		if ($std->execute ( $bound )) {
			while ( ( $row = $std->fetch () ) !== false ) {
				$list [] = $row;
			}
		}
		return $list;
	}
	public static function collateMemberRow($row) {
		$row ['fullname'] = $row ['firstname'] . ' ' . $row ['lastname'];
		$row ['fulladdress'] = $row ['zip'] . ' ' . $row ['city'] . '' . $row ['district'] . $row ['address'];
		$row ['fulltel'] = $row ['mobile'] . ' ' . $row ['phone'];
		$row ['country_name'] = self::$countries [$row ['country']];
		return $row;
	}
	public static function login($email, $password) {
		$db = Db::getInstance ();
		$result = array ();
		
		$sql = 'select * from av_member a where a.email = ?';
		$std = $db->prepare ( $sql );
		$bound = array (
				$email 
		);
		$std->execute ( $bound );
		$row = $std->fetch ();
		if ($row ['password'] != $password) {
			$result = array (
					'success' => false,
					'msg' => '密碼錯誤' 
			);
			return $result;
		}
		if (empty ( $row ['member_id'] )) {
			$result = array (
					'success' => false,
					'msg' => '無此帳號' 
			);
			return $result;
		}
		$result = array (
				'success' => true,
				'infor' => $row 
		);
		return $result;
	}
	public static function modify($member_id, $row) {
		$db = \Routine\Db::getInstance ();
		
		$fields = array (
				'country',
				'zip',
				'address',
				'phone',
				'mobile' 
		);
		$sql = 'update av_member set ' . $db->getUpdateColon ( $fields ) . '
				where member_id = :member_id';
		$bound = $db->getBound ( $fields, $row );
		$bound ['member_id'] = $member_id;
		$std = $db->prepare ( $sql );
		if (! $std->execute ( $bound )) {
			return false;
		}
		return true;
	}
	public static function register($row) {
		$db = \Routine\Db::getInstance ();
		
		$fields = array (
				'firstname',
				'lastname',
				'gender',
				'email',
				'password' 
		);
		$sql = 'insert into av_member (' . $db->getFieldsSeparateByDot ( $fields ) . ', `add_date`)
				values (' . $db->getColonFieldsSeparateByDot ( $fields ) . ', NOW())';
		$bound = $db->getBound ( $fields, $row );
		$std = $db->prepare ( $sql );
		if (! $std->execute ( $bound )) {
			return false;
		}
		$member_id = $db->lastInsertId ();
		return $member_id;
	}
	public static function registerSessionFromInfor($memInfor) {
		$_SESSION ['member_id'] = $memInfor ['member_id'];
		$_SESSION ['member_name'] = $memInfor ['firstname'] . ' ' . $memInfor ['lastname'];
	}
	public static function registerSession($member_id) {
		$_SESSION ['member_id'] = $member_id;
	}
	public static function getMemberId() {
		if (isset ( $_SESSION ['member_id'] )) {
			return $_SESSION ['member_id'];
		} else {
			return false;
		}
	}
}