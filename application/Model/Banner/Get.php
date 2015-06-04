<?php

namespace Model\Banner;

use Model\Db\Db;
use Model\Error\Error;

class Get {
	public static $statuses = array (
			'0' => array (
					'name' => '隱藏' 
			),
			'1' => array (
					'name' => '顯示' 
			) 
	);
	public static $types = array (
			'0' => array (
					'name' => '無連結' 
			), 
			'1' => array (
					'name' => '外部連結' 
			), 
			'2' => array (
					'name' => '連結至特店' 
			), 
			'3' => array (
					'name' => '連結至優惠券' 
			), 
	);
	public static function getList() {
		$db = Db::getInstance ();
		$sql = 'select * 
				from banner_index 
				where status = 1
				order by sort asc';
		$std = $db->prepare ( $sql );
		if ($std->execute ()) {
			$list = array ();
			while ( ( $row = $std->fetch () ) !== false ) {
				$list [] = $row;
			}
			return $list;
		} else {
			Error::warning ( $std->errorInfo () );
			return false;
		}
	}
}