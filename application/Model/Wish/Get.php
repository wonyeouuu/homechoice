<?php

namespace Model\Wish;

use Model\Db\Db;
use Model\Error\Error;

class Get {
	public static function getstatuslist() {
		$db = Db::getInstance ();
		
		$sql = 'select * from wish
				where 1
				order by voteCount desc';
		$std = $db->prepare ( $sql );
		if (! $std->execute ()) {
			Error::warning ( $std->errorInfo () );
			return false;
		}
		
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			if ($row ['status'] == '1') {
				$row ['status'] = '許願中';
				$row ['statusColor'] = array (
						0,
						255,
						0 
				);
			} elseif ($row ['status'] == '2') {
				$row ['status'] = '洽談中';
				$row ['statusColor'] = array (
						0,
						0,
						255 
				);
			} elseif ($row ['status'] == '3') {
				$row ['status'] = '洽談成功';
				$row ['statusColor'] = array (
						255,
						0,
						0 
				);
			}
			
			$list [] = $row;
		}
		return $list;
	}
	public static function getlist() {
		$db = Db::getInstance ();
		
		$sql = 'select * from wish 
				where 1
				order by voteCount desc';
		$std = $db->prepare ( $sql );
		if (! $std->execute ()) {
			Error::warning ( $std->errorInfo () );
			return false;
		}
		
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$list [] = $row;
		}
		return $list;
	}
}