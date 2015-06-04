<?php

namespace Routine;

class Db extends \PDO {
	protected static $_instance = null;
	public function __construct() {
		parent::__construct ( 'mysql:dbname=' . db_name . ';host=' . db_host . '', db_user, db_pw, array (
				\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
		) );
		$this->setAttribute ( \PDO::ATTR_CASE, \PDO::CASE_NATURAL );
		$this->setAttribute ( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING );
		$this->setAttribute ( \PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC );

		return $this;
	}
	private function __clone() {
	}
	public static function getInstance() {
		if (null === self::$_instance) {
			self::$_instance = new Db();
		}

		return self::$_instance;
	}
	
	public static function querySingle($sql, $bound=array()){
		$db=self::getInstance();
		$std=$db->prepare($sql);
		if($std->execute($bound)){
			return $std->fetch();
		}else{
			return false;
		}
		
		
	}
	public static function querySingleColumn($sql, $bound=array()){
		$db=self::getInstance();
		$std=$db->prepare($sql);
		if($std->execute($bound)){
			return $std->fetchColumn();
		}else{
			return false;
		}
		
		
	}
	
	public static function downSort($primary_id, $parent_id, $table, $primary_field, $parent_field, $sort_field){
		$db = self::getInstance();
		$sql = 'select `'.$sort_field.'`
				from `'.$table.'`
				where `'.$primary_field.'` = ?';
		$std=$db->prepare($sql);
		$bound=array($primary_id);
		if($std->execute($bound)){
			$nowIndex = $std->fetchColumn();
			
			
			$sql = "select `$primary_field`, `$sort_field` from `$table` where `$parent_field` = ? and `$sort_field` > ? order by `$sort_field` asc limit 1";
			$std = $db->prepare ( $sql );
			$bound = array (
					$parent_id,
					$nowIndex
			);
			if ($std->execute ( $bound )) {
				$nextRow = $std->fetch ();
				$sql = "update $table set `$sort_field` = ? where $primary_field = ?";
				$std = $db->prepare ( $sql );
				$bound = array (
						$nextRow [$sort_field],
						$primary_id
				);
				if (! $std->execute ( $bound )) {
					return false;
				}
				$sql = "update $table set `$sort_field` = ? where $primary_field = ?";
				$std = $db->prepare ( $sql );
				$bound = array (
						$nowIndex,
						$nextRow [$primary_field]
				);
				if (! $std->execute ( $bound )) {
					return false;
				}
				return true;
			}
		}
		return false;
	}
	public static function upSort($primary_id, $parent_id, $table, $primary_field, $parent_field, $sort_field){
		$db = self::getInstance();
		$sql = 'select `'.$sort_field.'`
				from `'.$table.'`
				where `'.$primary_field.'` = ?';
		$std=$db->prepare($sql);
		$bound=array($primary_id);
		if($std->execute($bound)){
			$nowIndex = $std->fetchColumn();
			
			
			$sql = "select `$primary_field`, `$sort_field` from `$table` where `$parent_field` = ? and `$sort_field` < ? order by `$sort_field` desc limit 1";
			$std = $db->prepare ( $sql );
			$bound = array (
					$parent_id,
					$nowIndex
			);
			if ($std->execute ( $bound )) {
				$nextRow = $std->fetch ();
				$sql = "update $table set `$sort_field` = ? where $primary_field = ?";
				$std = $db->prepare ( $sql );
				$bound = array (
						$nextRow [$sort_field],
						$primary_id
				);
				if (! $std->execute ( $bound )) {
					return false;
				}
				$sql = "update $table set `$sort_field` = ? where $primary_field = ?";
				$std = $db->prepare ( $sql );
				$bound = array (
						$nowIndex,
						$nextRow [$primary_field]
				);
				if (! $std->execute ( $bound )) {
					return false;
				}
				return true;
			}
		}
		return false;
	}
	
	public static function bindValuesFromData(&$std, $fields, $data){
		foreach ($fields as $field){
			if(isset($data[$field])){
				$std->bindValue($field, $data[$field]);
			}else{
				$std->bindValue($field, '');
			}
		}
	}

	public static function getRealFields($fields, $data){
		$realFields = array ();
		foreach ( $fields as $field ) {
			if (isset ( $data [$field] )) {
				$realFields [] = $field;
			}
		}
		return $realFields;
	}
	
	function getBound($field, $data) {
		$return = array();
		foreach ($field as $k => $v) {
			if (isset($data[$v])) {
				 
				$return[$v] = $data[$v];
			} else {
				$return[$v] = '';
			}
		}
		return $return;
	}

	/**
	 * 獲取以逗號分開的冒號statement :aa, :bb, :cc, :dd
	 *
	 * @param Array $a
	 * @return string
	 */
	public static function getColonFieldsSeparateByDot($a) {
		if (! is_array ( $a )) {
			return '';
		}
		foreach ( $a as $k => $v ) {
			$a [$k] = ':' . $v;
		}
		return implode ( ', ', $a );
	}

	/**
	 * 獲取update statement中set後接的冒號statement aa = :aa, bb = :bb, cc = :cc, dd = :dd
	 *
	 * @param Array $a
	 *        	欄位陣列
	 */
	public static function getUpdateColon($a = array()) {
		if (! is_array ( $a ))
			return '';

		$c = array ();
		foreach ( $a as $k => $v ) {
			$c [] = "`".$v . "` = :" . $v;
		}
		return implode ( ', ', $c );
	}

	/**
	 * 從一個陣列獲取以逗號分開的問號字串 ?, ?, ?, ?
	 *
	 * @param array $array
	 * @return string
	 */
	public static function getQuestionMarkByArray($array = array()) {
		if (empty ( $array )) {
			return '';
		}
		return implode ( ',', array_fill ( 0, count ( $array ), '?' ) );
	}

	/**
	 * 將陣列中每個元素前後加上` 並回傳以逗號隔開的字串 `a`, `b`, `c`, `d`
	 *
	 * @param Array $a
	 * @return string
	 */
	public static function getFieldsSeparateByDot($a = array()) {
		foreach ( $a as $k => $v ) {
			$a [$k] = '`' . $v . '`';
		}
		return implode ( ', ', $a );
	}

	/**
	 * 根據fields:Array的元素，查找object內是否有此屬性，有的話塞到bound裡
	 * @param array $fields 以欄位名稱為元素的陣列
	 * @param object $object
	 * @param array $bound
	 * @return array
	 */
	public static function makeBoundFromObject($fields, $object, $bound = array()) {
		if (! is_array ( $bound )) {
			return false;
		}
		foreach ( $fields as $field ) {
			if (isset ( $object->$field )) {
				$bound [$field] = $object->$field;
			}
		}
		return $bound;
	}
}