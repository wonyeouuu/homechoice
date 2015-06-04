<?php
if (function_exists ( 'date_default_timezone_set' ))
	date_default_timezone_set ( 'Asia/Taipei' );
define ( 'RootAbs', dirname ( dirname ( __FILE__ ) ) );
if ($_SERVER ['HTTP_HOST'] == 'localhost' || strpos ( $_SERVER ['HTTP_HOST'], 'jeff' ) !== false) {
	define ( 'WEB_URL', 'http://localhost/homechoice' );
	define ( 'API_URL', 'http://localhost/homechoice/api' );
	define ( 'TEST_URL', 'http://localhost/homechoice/test/' );
	define ( 'ADM_URL', 'http://localhost/homechoice/admin/' );
	define ( 'db_name', 'homechoice' );
	define ( 'db_host', 'localhost' );
	define ( 'db_user', 'root' );
	define ( 'db_pw', '123456' );
	define ( 'FileHost', 'http://localhost/homechoice' );
	define ( 'MediaUrl', FileHost . '/upload' );
	define('MediaAbs', RootAbs.'/upload');
} else {
	define ( 'WEB_URL', 'http://atc-event.com' );
	define ( 'API_URL', 'http://atc-event.com/api' );
	define ( 'TEST_URL', 'http://atc-event.com/test' );
	define ( 'ADM_URL', 'http://atc-event.com/admin' );
	define ( 'db_name', 'atcevent_homechoice' );
	define ( 'db_host', 'localhost' );
// 	define ( 'db_host', 'runnerdbinstance.cbapzjk3wlzl.ap-northeast-1.rds.amazonaws.com' );
// 	define ( 'db_user', 'umeshdb' );
// 	define ( 'db_pw', 'powertwumesh' );
	define ( 'db_user', 'atcevent_hc' );
	define ( 'db_pw', 'hc@001' );
	define ( 'FileHost', 'http://atc-event.com' );
	define ( 'MediaUrl', FileHost . '/upload' );
	define('MediaAbs', RootAbs.'/upload');
}

define ( 'WebCode', 'lp_' );

define ( 'UrlPreFix', '/homechoice' );
define ( 'AppDirAbs', RootAbs . '/application' );
define ( 'ModelDirAbs', AppDirAbs . '/model' );

define ( 'FileHost', 'http://file.umesh.tw' );
define ( 'MediaUrl', FileHost . '/images' );
function __autoload($name) {
// 	echo $name.'aidjsfij';
	$names = explode ( '\\', $name );
// 	print_r($names);
	$dirs = array (
			realpath ( RootAbs . '/application' )
	);
	foreach ( $dirs as $dir ) {
		$filePath = $dir;
		for($i = 0; $i <= count ( $names ); $i ++) {
			if ($i !== count ( $names )) {
				$filePath .= '/' . $names [$i];
			} else {
				$filePath .= $names [$i] . '.php';
			}
		}
		if (is_file ( $filePath )) {
// 			echo $filePath;
			require_once $filePath;
		}
	}
}
