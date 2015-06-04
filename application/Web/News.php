<?php

namespace Web;

class News {
	public static function getList() {
		$db = \Routine\Db::getInstance ();
		
		$sql = 'select * 
				from av_product_catagories a
				order by a.sort_number asc';
		$std = $db->prepare ( $sql );
		$std->execute ();
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$list [$row ['type']] [] = $row;
		}
// 		print_r($list);
		
		return $list;
	}
}