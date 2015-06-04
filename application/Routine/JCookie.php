<?php
namespace Routine;
class JCookie {
	public static function setCache($name, $value) {
		global $config;
	}
	public static function getCache($variableName, $defaultValue = '1', $prefix = null) {
		global $config;
		
		if($prefix !== null && !empty($prefix)){
			$cookieName = $prefix.'_'.$variableName;
		}
		elseif(isset($config['cookieName']) && !empty($config['cookieName'])){
			$cookieName = $config['cookieName'].'_'.$variableName;
		}else{
			$cookieName = $variableName;
		}
		
		if (isset ( $_REQUEST [$variableName] )) {
			$$variableName = $_REQUEST [$variableName];
			setcookie ( $cookieName, $$variableName, 0, '' );
		} elseif (isset ( $_COOKIE [$cookieName] )) {
			$$variableName = $_COOKIE [$cookieName];
		} else {
			$$variableName = $defaultValue;
		}
		return $$variableName;
	}
}