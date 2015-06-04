<?php
use Model\Db\Db;
require_once '../connect.php';
$db = Db::getInstance ();

$ResponseSet = new \Model\Response\Set ();
$UserIDs = getUserIDs ();
$CommunityIDs = getCommunitysFromUserID ( $UserIDs );
$CommunityCount=count($CommunityIDs);
for($i = 0; $i <= 2000; $i ++) {
// 	$UserID = $UserIDs[array_rand($UserIDs)];
	$Kind = mt_rand(1, 2);
	$Subject = generateRandomString(mt_rand(12, 20));
	$SubSubject = generateRandomString(mt_rand(15, 30));
	$ForWho = mt_rand(0, 1);
	$StartTimer = getRandomDate('2014-04-14', '2014-06-15');
	$EndTimer = getRandomDate('2014-05-14', '2014-08-15');
	$sql = 'insert into tb_Survey (`Kind`, `Subject`, `SubSubject`, `ForWho`, `CreateDateTime`, `CreateUserCode`, `StartTimer`, `EndTimer`)
			values (?, ?, ?, ?, NOW(), "TEST", ?, ?)';
	$std=$db->prepare($sql);
	$bound=array($Kind, $Subject, $SubSubject, $ForWho, $StartTimer, $EndTimer);
	if($std->execute($bound)){
		$SurveyID = $db->lastInsertId();
		if($Kind == '1'){//大B
			$questionCount=mt_rand(1, 5);
		}elseif($Kind == '2'){//小B
			$questionCount = 1;
		}
		for($z=1;$z<=$questionCount;$z++){
			$Index = $z;
			$Subject = generateRandomString(mt_rand(10, 20));
			$answerCount=mt_rand(2, 10);
			$Answers=array();
			$AnswerFields=array();
			for($j=1;$j<=$answerCount;$j++){
				$Answers[]=generateRandomString(mt_rand(6, 20));
				$AnswerFields[]='Answer'.$j;
			}
			$sql = 'insert into tb_SurveyQuestion (`SurveyID`, `Index`, `Subject`, `Kind`, ';
			$sql .= implode(', ', $AnswerFields);
			$sql .= ') values (?, ?, ?, ?, '.$db->getQuestionMarkByArray($AnswerFields).')';
			$std=$db->prepare($sql);
// 			$bound=array('SurverID'=>$SurveyID, 'Index'=>$Index, 'Subject'=>$Subject, 'Kind'=>mt_rand(1, 2));
			$bound=array($SurveyID, $Index,$Subject, mt_rand(1, 2));
			$bound=array_merge($bound, $Answers);
			$std->execute($bound);
		}
		
		if($ForWho == 1){
			$communityForCount=mt_rand(2, $CommunityCount);
			$communityRandomKeys=array_rand($CommunityIDs, $communityForCount);
			$commlist=array();
			foreach($communityRandomKeys as $comKey){
				$commlist[]=$CommunityIDs[$comKey];
			}
			$sql = 'insert ignore into tb_SurveyCommunity (`SurveyID`, `CommunityID`) values ';
			$stringArr=array();
			foreach ($commlist as $commid){
				$stringArr[]='('.$SurveyID.', '.$commid.')';
			}
			$sql .= implode(',', $stringArr);
			$std=$db->prepare($sql);
			
			$std->execute();
		}
		
		
		$sql = '';
	}
	
// 	$ResponseSet->addResponse ( $UserID, array('CommunityID'=>$CommunityID,'Subject'=>$Subject, 'Message'=>$Message) );
}

function getRandomDate($min, $max){
	$mini = strtotime($min);
	$maxi = strtotime($max);
	$int = mt_rand($mini, $maxi);
	$string = date('Y-m-d', $int);
	return $string;
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