<?php
class Grid {
	public $whereSql = '';
	public $groupSql = '';
	public $orderSql = '';
	public $countSql = '';
	public $querySql = '';
	public $bound = array ();
	public $searchData = array ();
	private $config;
	private $searchConfig = array ();
	private $searchNameInPost = 'search';
	private $ResetNameInGet = 'doing';
	public function __construct($config = array()) {
		if (! is_array ( $config )) {
			return false;
		}
		$this->config = $config + array (
				'tblName' => '',
				'pk' => '',
				'listUrl' => $_SERVER ['PHP_SELF'],
				'title' => '',
				'editUrl' => 'enter.php',
				'initWhere' => ' where 1',
				'initSort' => '   ',
				'initDesc' => ' ',
				'initGroup' => ' ',
				'cookieName' => '',
				'searchName' => 'search',
				'resetName' => 'doing',
				'pageName' => 'page' 
		);
	}
	public function execute() {
		$config = $this->config;
		extract ( $config );
		$where_sql = $config ['initWhere'];
		// echo 'is_search:' . $_REQUEST ['is_search'];
		if ($_GET [$resetName] == "reset") {
			$ST = "";
			$search = array ();
			setcookie ( $cookieName . "_list", "", 0 );
		} elseif ($_REQUEST ['is_search'] == "1") {
			if (is_array ( $_REQUEST [$searchName] ) && count ( $_REQUEST [$searchName] ) > 0) {
				$search = $_REQUEST [$searchName];
				$ST = serialize ( $_REQUEST [$searchName] );
				setcookie ( $cookieName . "_list", $ST, 0 );
				$where_sql = $this->set_where_sql ( $where_sql, $ST );
			}
		} elseif (isset ( $_COOKIE [$cookieName . '_list'] )) {
			if(get_magic_quotes_gpc() == 1){
				$_COOKIE[$cookieName.'_list'] = stripcslashes($_COOKIE[$cookieName.'_list']);
			}
			$search = unserialize ( $_COOKIE [$cookieName . '_list'] );
			$where_sql = $this->set_where_sql ( $where_sql, $_COOKIE [$cookieName . '_list'] );
		}
		$this->searchData = $search;
		
		$order_sql = $config ['initSort'] . $config ['initDesc'];
		if ($_GET [$resetName] == "reset") {
			$OT = "";
			$sort = array ();
			setcookie ( $cookieName . "_sort", "", 0 );
			setcookie ( $cookieName . "_desc", "0", 0 );
		} elseif (! empty ( $_GET ['sort'] )) {
			extract ( $_GET );
			if (! empty ( $_COOKIE [$cookieName . '_sort'] )) {
				if ($_COOKIE [$cookieName . '_sort'] == $sort) {
					if ($_COOKIE [$cookieName . '_desc'] == "1") {
						$desc = 0;
						setcookie ( $cookieName . "_desc", "0", 0 );
					} else {
						$desc = 1;
						setcookie ( $cookieName . "_desc", "1", 0 );
					}
				} else {
					setcookie ( $cookieName . "_sort", $sort, 0 );
					setcookie ( $cookieName . "_desc", "0", 0 );
					$desc = 0;
				}
			} else {
				setcookie ( $cookieName . "_sort", $sort, 0 );
				setcookie ( $cookieName . "_desc", "0", 0 );
				$desc = 0;
			}
			$order_sql = " order by " . $sort . " " . ($desc == "0" ? "asc" : "desc");
		} elseif (isset ( $_COOKIE [$cookieName . '_sort'] )) {
			$sort = $_COOKIE [$cookieName . '_sort'];
			$order_sql = " order by " . $_COOKIE [$cookieName . '_sort'] . " " . ($_COOKIE [$cookieName . '_desc'] == "0" ? "asc" : "desc");
		}
		
		if ($_GET [$resetName] == "reset") {
			$page = 1;
			setcookie ( $cookieName . "_page", "1" );
		} elseif (! empty ( $_GET ['page'] )) {
			$page = ( int ) $_GET ['page'];
			if (! empty ( $_COOKIE [$cookieName . '_page'] )) {
				setcookie ( $cookieName . "_page", $page );
			} else {
				setcookie ( $cookieName . "_page", "1" );
			}
		} elseif (! empty ( $_COOKIE [$cookieName . '_page'] )) {
			$page = $_COOKIE [$cookieName . '_page'];
		}
		
		/*
		 * elseif(isset($_COOKIE[$cookieName.'_list'])) { $ST = $_COOKIE[$cookieName.'_list']; $search = explode(',', $ST); $where_sql = set_where_sql($where_sql, $_COOKIE[$cookieName.'_list']); }
		 */
		
		// $where_sql .= " and a.proCatId > 0 ";
		
		$group_sql = $config ['initGroup'];
		// $count_sql = "select count(*) from " . $config ['tblName'] . " a " .
		// $where_sql . $group_sql;
		// $query_sql = "select a.*".$config['extraField']." from " . $config
		// ['tblName'] . " a " . $where_sql . $group_sql . $order_sql;
		$this->groupSql = $group_sql;
		$this->whereSql = $where_sql;
		$this->orderSql = $order_sql;
		// $this->bound = $bound;
		
		$this->countSql = $count_sql;
		$this->querySql = $query_sql;
	}
	/**
	 * 設定搜尋欄位及詳細情形
	 * @param array $searchConfig 搜尋欄位設定，以陣列表示
	 * <ul>
	 * <li><i><b>type</b></i> 設定此搜尋欄位的種類<ul>
	 * 	<li>like 用於前後皆%的like</li>
	 * 	<li>operator 需多指定<i><b>operator</b></i>欄位，如無指定則預設為"="</li>
	 * 	<li>null 用於判斷欄位是否為null</li></ul></li>
	 * <li><i><b>name</b></i> 搜尋欄位的名稱，在HTML中會自動轉換為search[name]
	 * <li><i><b>dbField</b></i> 要搜尋的資料庫欄位，如無指定會相當於name</li>
	 * <li><i><b>operator</b></i> 在type值設定為operator時有用，可指定為=、>、<、>=、<=</li>
	 * <li><i><b>customer</b></i> 客製化搜尋功能，客製程式寫在GridCustomer類別內</li>
	 * </ul>
	 */
	public function setSearch($searchConfig = array()) {
		$this->searchConfig = $searchConfig;
	}
	public function set_where_sql($where_sql, $par, $bound = array()) {
		$search = unserialize ( $par );
		
		foreach ( $this->searchConfig as $key => $value ) {
			if($value['type'] == 'customer'){
				$customerResult=GridCustomer::getCustomerWhereSql($value, $where_sql, $search, $this->bound);
				$this->bound=$customerResult['bound'];
				$where_sql = $customerResult['where_sql'];
			}
			if (! isset ( $value ['emptyValue'] )) {
				$value ['emptyValue'] = "";
			}
			if (! isset ( $value ['dbField'] )) {
				$value ['dbField'] = $value ['name'];
			}
			if (! isset ( $value ['searchName'] )) {
				$value ['searchName'] = $value ['name'];
			}
			// echo 'now value: ' . $value ['searchName'] . $search [$value
			// ['searchName']] . '<br /><br />';
			if (isset ( $value ['fieldType'] )) {
				switch ($value ['fieldType']) {
					case 'startDate' :
// 						$search [$value ['searchName']] = str_replace ( ' 00:00:00', '', $search [$value ['searchName']] );
						$search [$value ['searchName']] .= ' 00:00:00';// str_replace ( ' 00:00:00', '', $search [$value ['searchName']] );
						break;
					case 'endDate' :
// 						$search [$value ['searchName']] = str_replace ( ' 23:59:59', '', $search [$value ['searchName']] );
						$search [$value ['searchName']] .= ' 23:59:59';
						break;
					default :
						break;
				}
			}
			// echo 'now value: ' . $value ['searchName'] . $search [$value['searchName']] . '<br /><br />';
			if ($search [$value ['searchName']] != $value ['emptyValue']) {
				// echo 'now value: ' . $value ['searchName'] . $search [$value['searchName']] . '<br /><br />';
				switch ($value ['type']) {
					case 'operator' :
						if (! isset ( $value ['operator'] )) {
							$value ['operator'] = '=';
						}
						$where_sql .= " and (";
						if (is_string ( $value ['dbField'] )) {
							$where_sql .= " " . (! empty ( $value ['alias'] ) ? $value ['alias'] . '.' : '') . $value ['dbField'] . " " . $value ['operator'] . " :" . $value ['searchName'];
						} elseif (is_array ( $value ['dbField'] )) {
							$tmpa = array ();
							foreach ( $value ['dbField'] as $dbKey => $dbValue ) {
								$tmpa [] = (! empty ( $value ['alias'] ) ? $value ['alias'] . '.' : '') . $dbValue . " " . $value ['operator'] . " :" . $value ['searchName'];
							}
							$tmps = implode ( ' or ', $tmpa );
							$where_sql .= $tmps;
						}
						$where_sql .= ") ";
						// echo $where_sql;
						// print_r($search);
						$this->bound [$value ['searchName']] = $search [$value ['searchName']];
						break;
					case 'like' :
						$where_sql .= " and (";
						if (is_string ( $value ['dbField'] )) {
							$where_sql .= " " . (! empty ( $value ['alias'] ) ? $value ['alias'] . '.' : '') . $value ['dbField'] . " like :" . $value ['searchName'];
						} elseif (is_array ( $value ['dbField'] )) {
							$tmpa = array ();
							foreach ( $value ['dbField'] as $dbKey => $dbValue ) {
								$tmpa [] = (! empty ( $value ['alias'] ) ? $value ['alias'] . '.' : '') . $dbValue . " like :" . $value ['searchName'];
							}
							$tmps = implode ( ' or ', $tmpa );
							$where_sql .= $tmps;
						}
						$where_sql .= ") ";
						
						$this->bound [$value ['searchName']] = '%' . $search [$value ['searchName']] . '%';
						break;
					case 'null' :
						if ($search [$value ['searchName']] == $value ['isNull']) {
							$operator = ' is null ';
						} elseif ($search [$value ['searchName']] == $value ['isNotNull']) {
							$operator = ' is not null ';
						} else {
							break;
						}
						$where_sql .= " and (";
						if (is_string ( $value ['dbField'] )) {
							$where_sql .= " " . (! empty ( $value ['alias'] ) ? $value ['alias'] . '.' : '') . $value ['dbField'] . "  " . $operator;
						} elseif (is_array ( $value ['dbField'] )) {
							$tmpa = array ();
							foreach ( $value ['dbField'] as $dbKey => $dbValue ) {
								$tmpa [] = (! empty ( $value ['alias'] ) ? $value ['alias'] . '.' : '') . $value ['dbField'] . "  " . $operator;
							}
							$tmps = implode ( ' or ', $tmpa );
							$where_sql .= $tmps;
						}
						$where_sql .= ") ";
						
						// $this->bound [$value ['searchName']] = '%' . $search
						// [$value ['searchName']] . '%';
						break;
					default :
						break;
				}
				
				// echo '<br /><br />here';
				// print_r($this->bound);
			}
		}
		
		return $where_sql;
	}
	public function setConfig($config) {
		$this->config += $config;
	}
	public function setInitWhereSql($sql) {
		$this->config ['initWhere'] = $sql;
	}
	public function setCookieName($name) {
		$this->config ['cookieName'] = $name;
	}
	public function setSearchNameInPost($name) {
		$this->config ['searchName'] = $name;
	}
	public function setResetNameInGet($name) {
		$this->config ['resetName'] = $name;
	}
}

?>