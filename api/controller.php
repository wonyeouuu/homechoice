<?php
// header("Access-Control-Allow-Origin: http://runner.touchinc.com.tw");
// header('Access-Control-Allow-Credentials: true');
// header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

require_once dirname(__FILE__).'/connect.php';
if (function_exists ( 'date_default_timezone_set' ))
	date_default_timezone_set ( 'Asia/Taipei' );


class Control {
	static function exceptionResponse($statusCode, $message) {
		header ( "HTTP/1.0 {$statusCode} {$message}" );
		echo "{$statusCode} {$message}";
		exit ();
	}
	function index() {
		echo 'index...';
	}
}
interface RESTfulInterface {
	public function restGet($segments);
	public function restPut($segments);
	public function restPost($segments);
	public function restDelete($segments);
}
class Container extends Control {
	private $control = false;
	private $segments = false;
	function __construct() {
		if (! isset ( $_SERVER ['REQUEST_URI'] ) or $_SERVER ['REQUEST_URI'] == '/') {
			// $this->control = $this->segments = false;
			return;
		}
		
		$requestUri = str_replace(UrlPreFix, '', strtolower($_SERVER['REQUEST_URI']));
		
		$this->segments = explode ( '/', $requestUri );
// 		print_r($this->segments);
		array_shift ( $this->segments ); // first element always is an empty string.
// 		print_r($this->segments);
		
		if(!isset($this->segments[1]) || !isset($this->segments[2])){
			self::exceptionResponse(503, 'Service Unavailable.');
		}
		error_log(print_r($this->segments, true));
		$Classname = ucfirst($this->segments[1]);
// 		$Actionname = $this->segments[2];
		$Classpath = __DIR__.'/'.$Classname.'.php';
// 		echo $Classpath;
// 		echo __NAMESPACE__;
		
		if(file_exists(__DIR__.'/'.$Classname.'.php')){
			
			require_once __DIR__.'/'.$Classname.'.php';
		}
		error_log('/'.$Classname.'.php');
		error_log((is_file(__DIR__.'/'.$Classname.'.php'))?'true':'false');
// 		echo $Classname;
		
		
// 		$controlName = ucfirst ( array_shift ( $this->segments ) );
// 		echo '<br />';
// 		echo 'control name = '.$controlName;
// 		echo '<br />';
// 		if (! class_exists ( $controlName )) {
// 			echo $controlFilepath = $controlName . '.php';
// 			echo '<br />';
// 			echo ModelDirAbs.'/Mock.php';
// 			echo '<br />';
// 			echo is_file(ModelDirAbs.'/Mock.php') ? 'exist':'not';
// 			echo '<br />';
// 			if (file_exists (ModelDirAbs.'/'. $controlFilepath )) { // 載入客戶要求的 control
// 				echo 'abc';
// 				require_once $controlFilepath;
// 			} else { // 找不到客戶要求的 control
// 				self::exceptionResponse ( 503, 'Service Unavailable!' );
// 				// 回傳 501 (Not Implemented) 或 503.
// 				// See also: RFC 2616
// 			}
// 		}
// 		echo '<br>';
		$ClassIncludePath = '\\Control\\'.$Classname;
// 		echo '<br>';
// 		$this->control = new $z ();
		$this->control = new $ClassIncludePath();
// 		$a = new NN();
	}
	function index() {
		echo 'index.php/{control name}/{object id}';
	}
	function run() {
		if ($this->control === false) {
			return $this->index ();
		}
		
		if (empty ( $this->segments )) { // Without parameter
			return $this->control->index ();
		}
		
		// request resource by RESTful way.
		// $method = $this->restMethodname;
// 		echo $this->control;
		
		$method =  ucfirst ( strtolower ( $this->segments[2] ) );
		if (! method_exists ( $this->control, $method )) {
			self::exceptionResponse ( 405, 'Method not Allowed!' );
		}
		
		$arguments = $this->segments;
		
		$this->control->$method ( $arguments );
	}
} // end class Container

$container = new Container ();

$container->run ();
?>
