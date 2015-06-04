<?php

// CONFIGURATION for SmartAdmin UI

// ribbon breadcrumbs config
// array("Display Name" => "URL");
$breadcrumbs = array (
		"首頁" => APP_URL 
);

/*
 * navigation array config ex: "dashboard" => array( "title" => "Display Title", "url" => "http://yoururl.com", "icon" => "fa-home" "label_htm" => "<span>Add your custom label/badge html here</span>",
 * "sub" => array() //contains array of sub items with the same format as the parent )
 */
$page_nav = array (
		"dashboard" => array (
				"title" => "首頁",
				"url" => APP_URL,
				"icon" => "fa-home" 
		),
		"account" => array (
				'title' => '後台帳號管理',
				'icon' => 'fa-wrench',
				'url' => APP_URL . '/account-list.php',
				'permission' => 'SAC' 
		),
		"source" => array (
				"title" => "安裝來源記錄",
				"icon" => "fa-building-o",
				"url" => APP_URL . '/install-list.php',
				'permission' => 'SSR' 
		),
		"capacity" => array (
				"title" => "使用者身分記錄",
				"icon" => "fa-user-secret",
				"url" => APP_URL . '/memberlogin-list.php',
				'permission' => 'SUC' 
		),
// 		"member" => array (
// 				"title" => "會員列表",
// 				"icon" => "fa-user",
// 				"url" => APP_URL . '/member-list.php',
// 				'permission' => 'SMB' 
// 		),
		"click" => array (
				"title" => "點擊記錄",
				"icon" => 'fa-hand-o-up' ,
				"url" => APP_URL . '/click-list.php',
				'permission' => 'SCR' 
		),
// "album" => array (
// "title" => "相本訊息",
// 'icon' => 'fa-picture-o',
// 'sub' => array (
// 'addalbum' => array (
// 'title' => '發佈相本',
// 'url' => APP_URL . '/album-edit.php',
// 'permission' => 'EMG'
// ),
// 'albumlist' => array (
// 'title' => '相本列表',
// 'url' => APP_URL . '/album-list.php',
// 'permission' => 'SMG'
// )
// )
// ),
// 'user' => array (
// 'title' => '會員管理列表',
// 'icon' => 'fa-user',
// 'sub' => array (
// 'user-list' => array (
// 'title' => '會員管理列表',
// 'permission' => '',
// 'url' => APP_URL . '/user-list.php'
// )
// )
// ),
// 'community' => array (
// 'title' => '社團、社區管理列表',
// 'icon' => 'fa-users',
// 'sub' => array (
// 'community-list' => array (
// 'title' => '社團管理列表',
// 'permission' => '',
// 'url' => APP_URL . '/community-list.php'
// ),
// 'communitytype-list' => array (
// 'title' => '社團類別管理',
// 'permission' => '',
// 'url' => APP_URL . '/communitytype-list.php'
// )
// )
// ),
// 'survey' => array (
// 'title' => '民調管理列表',
// 'icon' => 'fa-bar-chart-o',
// 'sub' => array (
// 'survey-list' => array (
// 'title' => '民調問卷管理列表',
// 'permission' => '',
// 'url' => APP_URL . '/survey-list.php'
// )
// )
// ),
// 'calendar' => array (
// 'title' => '行事曆管理',
// 'icon' => 'fa-calendar',
// 'sub' => array (
// 'calendar-list' => array (
// 'title' => '行事曆',
// 'permission' => '',
// 'url' => APP_URL . '/calendar-list.php'
// )
// )
// )
);

// configuration variables
$page_title = "";
$page_css = array ();
$no_main_header = false; // set true for lock.php and login.php
$page_body_prop = array (); // optional properties for <body>
?>