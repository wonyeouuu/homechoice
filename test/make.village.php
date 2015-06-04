<?php
use Model\Db\Db;
require_once '../connect.php';
$db = Db::getInstance ();

$sql = 'select * from tb_City order by Sort asc';
$std = $db->prepare ( $sql );
$std->execute ();
$list = array ();
while ( ( $row = $std->fetch () ) !== false ) {
	$list [$row ['CityCode']] = array (
			'label' => $row ['CityName'],
			'sub' => array () 
	);
	$sql = 'select * from tb_District where CityCode = "' . $row ['CityCode'] . '"';
	$distd = $db->prepare ( $sql );
	$distd->execute ();
	while ( ( $row2 = $distd->fetch () ) !== false ) {
		$list [$row ['CityCode']] ['sub'] [$row2['DistrictCode']] = array (
				'label' => $row2 ['District'],
				'sub' => array () 
		);
		$sql = 'select * from tb_Village where DistrictNo = "' . $row2 ['DistrictCode'] . '"';
		$vistd = $db->prepare ( $sql );
		$vistd->execute ();
		while ( ( $row3 = $vistd->fetch () ) !== false ) {
			$list [$row ['CityCode']] ['sub'] [$row2 ['DistrictCode']]['sub'][$row3['QuickNo']] = array (
					'label' => $row3 ['Village']
			);
		
		}
	}
}

$fp = fopen('umesh.quickno.js', 'w+');
if($fp){
	fwrite($fp, json_encode($list));
	fclose($fp);
}