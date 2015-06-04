<?php

namespace Model\Db;

class Db extends \PDO {
	protected static $_instance = null;
	public function __construct() {
		parent::__construct ( 'mysql:dbname=' . db_name . ';host=' . db_host . '', db_user, db_pw, array (
				\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'' 
		) ) ;
		
		$this->setAttribute ( \PDO::ATTR_CASE, \PDO::CASE_NATURAL );
		$this->setAttribute ( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING );
		$this->setAttribute ( \PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC );
		
		return $this;
	}
	private function __clone() {
	}
	public static function getInstance() {
		if (null === self::$_instance) {
			self::$_instance = new Db ();
		}
		print_r($this);
		
		return self::$_instance;
	}
	public static function bindValuesFromData(&$std, $fields, $data) {
		foreach ( $fields as $field ) {
			if (isset ( $data [$field] )) {
				$std->bindValue ( $field, $data [$field] );
			} else {
				$std->bindValue ( $field, '' );
			}
		}
	}
	public static function getRealFields($fields, $data) {
		$realFields = array ();
		foreach ( $fields as $field ) {
			if (isset ( $data [$field] )) {
				$realFields [] = $field;
			}
		}
		return $realFields;
	}
	public function bindValues($std, $fields, $data) {
		if (get_class ( $std ) != 'PDOStatement') {
			return false;
		}
		$return = array ();
		$db = self::getInstance ();
		$std = $db->prepare ( $sql );
		foreach ( $fields as $fieldKey => $fieldRow ) {
			if (is_string ( $fieldRow )) { // 如果該設定是字串
				if (isset ( $data [$fieldRow] )) {
					$std->bindValue ( $fieldRow, $data [$fieldRow], \PDO::PARAM_STR );
				} else {
					$std->bindValue ( $fieldRow, '' );
				}
			} elseif (is_array ( $fieldRow )) { // 如果該設定是陣列
				$fieldName = $fieldRow ['name'];
				$fieldType = isset ( $fieldRow ['type'] ) ? $fieldRow ['type'] : \PDO::PARAM_STR;
				if (isset ( $data [$fieldName] )) { // 如果原始送過來的資料有相符合的值
					$std->bindValue ( $fieldName, $data [$fieldName], $fieldType );
				} else {
					switch ($fieldType){
						case \PDO::PARAM_NULL://0
							$std->bindValue($fieldName, null, \PDO::PARAM_INT);//用PDO::PARAM_NULL一些mysql版本會綁不過去
							break;
						case \PDO::PARAM_INT:
							$std->bindValue($fieldName, 0, \PDO::PARAM_INT);
							break;
						case \PDO::PARAM_STR:
							$std->bindValue($fieldName, '', \PDO::PARAM_STR);
							break;
						case \PDO::PARAM_BOOL:
							$std->bindValue($fieldName, true, \PDO::PARAM_BOOL);
							break;
					}
				}
			}
		}
		return $std;
	}
	function getBound($field, $data) {
		$return = array ();
		foreach ( $field as $k => $v ) {
			if (isset ( $data [$v] )) {
				
				$return [$v] = $data [$v];
			} else {
				$return [$v] = '';
			}
		}
		return $return;
	}
	
	public static function checkRequireFields(array $requireFields, $data){
		foreach ($requireFields as $key => $name){
			if(!isset($data[$name])){
				return false;
			}
		}
		return true;
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
			$c [] = "`" . $v . "` = :" . $v;
		}
		return implode ( ', ', $c );
	}
	
	/**
	 * 從一個陣列獲取以逗號分開的問號字串 ?, ?, ?, ?<br />
	 * 陣列有幾個元素，就返回幾個問號，前後要自己加(跟)
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
	 *
	 * @param array $fields
	 *        	以欄位名稱為元素的陣列
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