<?php

namespace Model\Member;

use Model\Db\Db;

class Data {
	public static function login($row) {
		$_SESSION ['email'] = $row ['email'];
		$_SESSION ['capacity'] = $row ['capacity'];
		$_SESSION ['system'] = $row ['system'];
		return self::addLoginRecord ( $row );
	}
	public static function addLoginRecord($row) {
		$db = Db::getInstance ();
		
		if (empty ( $row ['mId'] ) || empty ( $row ['system'] )) {
			return false;
		}
		
		$sql = 'insert into member_login (`mId`, `system`, `capacity`, `addTime`, `addDate`) values (:mId, :system, :capacity, NOW(), NOW())';
		$std = $db->prepare ( $sql );
		$std->bindValue ( 'mId', $row ['mId'] );
		$std->bindValue ( 'capacity', $row ['capacity'] );
		$std->bindValue ( 'system', $row ['system'] );
		return $std->execute ();
	}
	public static function getMemberByEmail($email) {
		$db = Db::getInstance ();
		$sql = 'select * from member where email = :email';
		$std = $db->prepare ( $sql );
		$std->bindValue ( 'email', $email );
		$std->execute ();
		return $std->fetch ();
	}
	public static function addMember($email, $capacity, $system) {
		$db = Db::getInstance ();
		
		$sql = 'replace into member (`email`, `capacity`, `addDate`, `system`) values (:email, :capacity, NOW(), :system)';
		
		$std = $db->prepare ( $sql );
		$std->bindValue ( 'email', ( string ) $email );
		$std->bindValue ( 'capacity', ( int ) $capacity );
		$std->bindValue ( 'system', ( int ) $system );
		return $std->execute ();
	}
}