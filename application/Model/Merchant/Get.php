<?php

namespace Model\Merchant;

use Model\Db\Db;
use Model\Error\Error;

class Get {
	public static $slogans = array (
			'0' => array (
					'name' => '' 
			),
			'1' => array (
					'name' => 'HOT' 
			),
			'2' => array (
					'name' => 'NEW' 
			) 
	);
	public static $categories = array (
			'1' => array (
					'name' => '美食饗宴' 
			),
			'2' => array (
					'name' => '3C購物' 
			),
			'3' => array (
					'name' => '百貨購物' 
			),
			'4' => array (
					'name' => '旅遊觀光' 
			) 
	);
	public static $cityIds = array (
			1,
			2,
			3,
			4,
			5,
			6,
			7,
			8,
			9,
			10,
			11,
			12,
			13,
			14,
			15,
			16,
			17,
			18,
			19,
			20,
			21,
			22 
	);
	
	public static function getCitys(){
		$db = Db::getInstance();
		
		$sql = 'select *
				from city a
				order by a.sort asc
				';
		
		$std = $db->prepare ( $sql );
		$bound = array (
		);
		$list = array ();
		if ($std->execute ( $bound )) {
			while ( ( $row = $std->fetch () ) !== false ) {
				$list [$row['cityId']] = $row['name'];
			}
		}
		return $list;
	}
	
	public static function getLocations($mId){
		$db = Db::getInstance();
		
		$sql = 'select b.name merchantName, a.*
				from merchant_location a
				left join merchant b on a.mId = b.mId
				where a.mId = ?
				order by a.mlId asc
				';
		
		$std = $db->prepare ( $sql );
		$bound = array (
				$mId
		);
		$list = array ();
		if ($std->execute ( $bound )) {
			while ( ( $row = $std->fetch () ) !== false ) {
				$list [] = $row;
			}
		}
		return $list;
	}
	
	public static function getList($cityId = null) {
		$db = Db::getInstance ();
		$sql = 'select a.*, group_concat(b.cityId) cityIds, group_concat(c.name) locName, group_concat(c.lantitude) locLantitude, group_concat(c.longitude) locLongitude ';
		$sql .= ' from merchant a';
		// if($cityId !==null){
		$sql .= ' left join merchant_city_map b on a.mId = b.mId';
		// }else{
		// $sql .= ' left join merchant_city_map b ';
		// }
		$sql .= ' left join merchant_location c on a.mId = c.mId';
		$sql .= ' where (a.status = 1 or a.status = 2)';
		if ($cityId !== null) {
			$sql .= ' and (b.cityId = :cityId or b.cityId is null)';
		}
		$sql .= ' group by a.mId';
		$sql .= ' order by a.addDate desc';
		// echo $sql;
		$std = $db->prepare ( $sql );
		$bound = array ();
		if ($cityId !== null) {
			$bound ['cityId'] = $cityId;
		}
		
		foreach ( self::$cityIds as $k => $v ) {
			self::$cityIds [$k] = ( string ) $v;
		}
		
		if (! $std->execute ( $bound )) {
			Error::warning ( $std->errorInfo () );
			return false;
		}
		$list = array ();
		while ( ( $row = $std->fetch () ) !== false ) {
			$row ['citys'] = explode ( ',', $row ['cityIds'] );
			if (empty ( $row ['cityIds'] )) {
				$row ['citys'] = self::$cityIds;
			}
			$row ['citys'] = array_unique ( $row ['citys'] );
			$row['citys'] = array_values($row['citys']);
			$locNames = explode ( ',', $row ['locName'] );
			$locLantitudes = explode ( ',', $row ['locLantitude'] );
			$locLongitudes = explode ( ',', $row ['locLongitude'] );
			$row ['locations'] = array ();
			foreach ( $locNames as $key => $value ) {
				if (empty ( $value ))
					break;
				$row ['locations'] [] = array (
						'name' => $value,
						'lantitude' => $locLantitudes [$key],
						'longitude' => $locLongitudes [$key] 
				);
			}
			$list [] = $row;
		}
		return $list;
	}
	public static function getCategoryList() {
		$db = Db::getInstance ();
		$sql = 'select a.cId, a.name, a.sort ';
		$sql .= ' from merchant_category a';
		$sql .= ' order by sort asc';
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
	public static function getIndexList($cityId = null) {
		$db = Db::getInstance ();
		$sql = 'select a.mId id, a.name, a.slogan, 1 type ';
		$sql .= ' from merchant a ';
		if ($cityId !== null) {
			$sql .= ' left join merchant_city_map b on a.mId = b.mId';
		}
		$sql .= ' where a.status = 2';
		if ($cityId !== null) {
			$sql .= ' and b.cityId = :cityId';
			$sql .= ' group by a.mId';
		}
		$sql .= ' order by a.addDate desc';
		$sql .= ' limit 6';
		$std = $db->prepare ( $sql );
		$bound = array ();
		if ($cityId !== null) {
			$bound ['cityId'] = $cityId;
		}
		if (! $std->execute ( $bound )) {
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