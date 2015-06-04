<?php
namespace Model\Source;

use Model\Db\Db;
class Source{
	
	
	
	public static function logInstallSource($system, $sId){
		$db = Db::getInstance();
		
		$sql = 'insert into install_source (`installDate`, `installTime`, `system`, `sId`) values (NOW(), NOW(), :system, :sId)';
		
		$std=$db->prepare($sql);
		$std->bindValue('system', (int)$system);
		$std->bindValue('sId', (int)$sId);
		return $std->execute();
	}
	
	public static function getSourceList(){
		$db = Db::getInstance();
		
		$sql = 'select * from source ';
		$std=$db->prepare($sql);
		$std->execute();
		$list=array();
		while(($row=$std->fetch())!==false){
			$list[]=$row;
		}
		return $list;
		
	}
}