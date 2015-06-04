<?php

namespace Routine;

class Permission {
	public static $permissions = array (
			'account' => array (
					'title' => '帳號管理',
					'sub' => array (
							array (
									'title' => '觀看帳號列表',
									'code' => 'SAC' 
							),
							array (
									'title' => '修改帳號',
									'code' => 'EAC' 
							) 
					) 
			),
			'source' => array (
					'title' => '安裝來源',
					'sub' => array (
							array (
									'title' => '觀看安裝來源',
									'code' => 'SSR' 
							),
							array (
									'title' => '匯出安裝來源',
									'code' => 'ESR' 
							),
					) 
			),
			'capacity' => array (
					'title' => '使用者身分記錄',
					'sub' => array (
							array (
									'title' => '觀看使用者身分',
									'code' => 'SUC' 
							),
							array (
									'title' => '匯出',
									'code' => 'EUC' 
							) ,
					) 
			),
			'member' => array (
					'title' => '會員列表',
					'sub' => array (
							array (
									'title' => '觀看會員列表',
									'code' => 'SMB' 
							),
							array (
									'title' => '修改會員列表',
									'code' => 'MMB' 
							) ,
					) 
			),
			'click' => array (
					'title' => '點擊記錄列表',
					'sub' => array (
							array (
									'title' => '觀看記錄列表',
									'code' => 'SCR' 
							),
							array (
									'title' => '匯出記錄列表',
									'code' => 'ECR' 
							) ,
					) 
			),
	);
	public static 

	function explodeSessionToArray($permissionInSession) {
		$permissionInSession = ( string ) $permissionInSession;
		$permissionArray = explode ( ',', $permissionInSession );
		return $permissionArray;
	}
	public function unRegisterSession() {
		foreach ( $_SESSION as $key => $value ) {
			if (substr ( $key, 0, 5 ) == WebCode . 'bk') {
				unset ( $_SESSION [$key] );
			}
		}
	}
	public static function registerBackSession($row) {
		$_SESSION [WebCode . 'bkemail'] = $row ['email'];
		$_SESSION [WebCode . 'bkname'] = $row ['name'];
		$_SESSION [WebCode . 'bkaccount_id'] = $row ['account_id'];
		$_SESSION [WebCode . 'bkaccount'] = $row ['account'];
		$permission = $row ['permission'];
		if (is_string ( $permission )) {
			$permission = explode ( ',', $permission );
			$_SESSION [WebCode . 'bkpermission'] = $permission;
		} elseif (is_array ( $permission )) {
			$_SESSION [WebCode . 'bkpermission'] = $permission;
		}
	}
	public static function checkPermission($permission, $header = false) {
		if (in_array ( $permission, $_SESSION [WebCode . 'bkpermission'] )) {
			return true;
		} else {
			if ($header === true) {
				header ( 'Location: login.php' );
				exit ();
			}
			return false;
		}
	}
	public static function isLogined($header = true) {
		if (isset ( $_SESSION [WebCode . 'bkaccount_id'] ) && ! empty ( $_SESSION [WebCode . 'bkaccount_id'] )) {
			return true;
		}
		if ($header === true) {
			header ( 'Location: login.php' );
			go_to ( 'login.php' );
			exit ();
		}
		return false;
	}
}