<?php
namespace Control;

use Model\Feedback;
class Source{
	public function logsource(){
		$system = $_POST['system'];
		$sId = $_POST['sId'];
		$result=\Model\Source\Source::logInstallSource($system, $sId);
		Feedback::feedback($result);
	}
	public function getsourcelist(){
		$list=\Model\Source\Source::getSourceList();
		Feedback::feedback($list);
	}
	
	
	public static function getbeerbar(){
		
	}
}