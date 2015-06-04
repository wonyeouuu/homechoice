<?php
use Model\Db\Db;
require_once '../connect.php';
$db = Db::getInstance ();

$ResponseSet = new \Model\Message\Set ();
$UserIDs = getUserIDs ();
$CommunityIDs = getCommunitysFromUserID ( $UserIDs );
for($i = 0; $i <= 50000; $i ++) {
	$UserID = $UserIDs[array_rand($UserIDs)];
	$CommunityID = $CommunityIDs[array_rand($CommunityIDs)];
	$Subject = generateRandomString(10);
	$Message = generateRandomString(50);
	
	$ResponseSet->addResponse ( $UserID, array('CommunityID'=>$CommunityID,'Subject'=>$Subject, 'Message'=>$Message) );
}
function generateRandomString($length = 10) {
	$characters = '012 3456789 abcdefghi jklm nopqr stuvw xyzA BCDEFGHIJKLMNOPQ RSTUVWXYZ ';
	$randomString = '';
	for($i = 0; $i < $length; $i ++) {
		$randomString .= $characters [rand ( 0, strlen ( $characters ) - 1 )];
	}
	return $randomString;
}
function getCommunitysFromUserID($UserIDs) {
	global $db;
	
	if (! is_array ( $UserIDs ) || count ( $UserIDs ) == 0) {
		return array ();
	}
	
	$sql = 'select group_concat(CommunityID) CommunityIDs
			from tb_UserCommunity a
			where a.UserID in (' . implode ( ',', $UserIDs ) . ')';
	$std = $db->prepare ( $sql );
	$std->execute ();
	$communityidstring = $std->fetchColumn ();
	$CommunityIDs = explode ( ',', $communityidstring );
	return $CommunityIDs;
}
function getUserIDs() {
	global $db;
	$sql = 'select group_concat(UserID) UserIds from tb_User';
	$std = $db->prepare ( $sql );
	$std->execute ();
	$useridstring = $std->fetchColumn ();
	$UserIDs = explode ( ',', $useridstring );
	return $UserIDs;
}