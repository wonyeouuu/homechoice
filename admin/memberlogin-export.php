<?php
use Model\Db\Db;
require_once '../connect.php';
// ini_set ( 'auto_detect_line_endings', true );
$afId = $_GET ['afId'];
$db = Db::getInstance ();

$sql= 'select a.*, b.name sourceName
			from member_login a
			left join source b on a.sId = b.sId';
$std=$db->prepare($sql);
$std->execute();
$list=array();
while(($row=$std->fetch())!==false){
	$row['systemName'] = \Model\System\Storage::$systems[$row['sId']]['name'];
	
	$list[]=$row;
}


$filename = "使用者身分-" . date ( "Ymd" ) . ".csv"; // 這行可以將下載的檔案自動加上匯出時的日期時間，方便檔案管理做區分
$fp = fopen ( 'php://output', 'w' );
// fwrite($fp, "\xEF\xBB\xBF");
$titles=array('記錄日期', '系統', '安裝來源');
foreach ( $titles as $key => $value ) {
	$titles [$key] = mb_convert_encoding ( $value, 'big5', 'utf-8' );
}
fputcsv ( $fp, $titles );
foreach($list as $row){
	$datas=array($row['addDate'], $row['systemName'], $row['sourceName']);
	foreach ( $datas as $key => $value ) {
		$datas [$key] = mb_convert_encoding ( $value, 'big5', 'utf-8' );
	}
	fputcsv ( $fp, $datas );
}
fclose ( $fp );

// ob_clean();
header ( 'Content-type: application/csv; charset=utf-8' );
header ( 'Content-Disposition: attachment; filename=' . $filename );
// readfile ( $filename );
exit ();
	


// header("Pragma: public");
// header("Expires: 0");
// header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
// header("Content-Type: application/force-download");
// header("Content-Type: application/octet-stream");
// header("Content-Type: application/download");
// header("Content-Disposition: attachment;filename=enter.php ");
// header("Content-Transfer-Encoding: binary ");