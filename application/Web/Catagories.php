<?php

namespace Web;

class Catagories {
	public static function getList() {
		$db = \Routine\Db::getInstance ();
		
		$sql = 'select * 
				from av_product_catagories a
				where a.status = 1
				order by a.sort_number asc';
		$std = $db->prepare ( $sql );
		$std->execute ();
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$row ['image_url'] = \Routine\FileField::getImageRel ( $row ['image'], 'catagories' );
			$list [$row ['type']] [] = $row;
		}
// 		print_r($list);
		
		return $list;
	}
}