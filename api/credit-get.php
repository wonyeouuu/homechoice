<?php
require_once '../connect.php';

$fp = fopen('credit.log', 'a+');
if($fp){
	fwrite($fp, print_r($_REQUEST, true));
	fclose($fp);
}

$OrderSet = new Model\Order\Set();

if($OrderSet->replyBuysafe($_REQUEST)){
	echo '0000';
}