<?php

namespace Model\Log;

class Log {
	protected static $logFile = 'log.html';
	protected static $errorFile = 'error.html';
	protected static $requestFile = 'request.html';
	public static function log($o, $type = 'log') {
		$filename = $type.'File';
		$logFile = RootAbs . '/logs/' . self::$$filename;
		if(is_string($o)){
			
		}elseif(is_array($o)|| is_object($o)){
			$o = json_encode($o);
		}
		
		if (( $fp = fopen ( self::getLogFile (), 'a+' ) ) !== false) {
			$head = '<pre><span style="font-weight: bold;">[' . date ( 'Y-m-d H:i:s' ) . ']</span>';
			$msg =  ( $o ) . "\r\n";
			$bottom = '</pre>';
			fwrite ( $fp, $head . $msg );
			fclose ( $fp );
		}
	}
	
	public static function error($o){
		self::log($o, 'error');
	}
	public static function request($o){
		self::log($o, 'request');
	}
	
	protected static function getLogFile(){
		return RootAbs.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'log.html';
	}
}