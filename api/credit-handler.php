<?php
require_once 'connect.php';

if (isset ( $_REQUEST ['web'] )) {
	$odrId = ( int ) $_REQUEST ['Td'];
	$rTotal = ( int ) $_REQUEST ['MN'];
	$errcode = $_REQUEST ['errcode'];
	
	$OrderSet = new Model\Order\Set ();
	$OrderSet->replyBuysafe ( $_REQUEST );
	
	if ($_REQUEST ['note2'] == 'joeec') {
		if ($errcode == '00') {
				
			// shopping_history.php
			// shoppingsuccess.php
			echo "<script>alert(\"刷卡成功\");\r\n<\/script>;";
			header ( 'Location: http://www.umesh.com.tw/checkout_process.php' );
			exit ();
		} else {
			echo "<script>alert(\"刷卡失敗\");\r\n<\/script>;";
			header ( 'Location: http://www.umesh.com.tw/checkout_payment.php' );
			exit ();
		}
	} else {
		if ($errcode == '00') {
			
			// shopping_history.php
			// shoppingsuccess.php
			
			header ( 'Location: http://umesh.tw/shopping_history.html?result=1' );
			exit ();
		} else {
			header ( 'Location: http://umesh.tw/shopping_history.html?result=0' );
			exit ();
		}
	}
}