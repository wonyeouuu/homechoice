<?php
if (is_file ( '../config/config.php' )) {
	require_once '../config/config.php';
	
} elseif (is_file ( '../api/config/config.php' )) {
	require_once '../api/config/config.php';
}
$root = dirname(dirname(__FILE__));
$classes = array (
		'User.php',
		'Message.php',
		'Calendar.php' 
);
$exceptFile = array (
		'connect.php',
		'controller.php',
		'index.php' ,
		'credit-get.php',
		'credit-handler.php',
		'testfile.php'
);
$classList = array ();

if(is_dir($root.'/api/')){
	$classDir = $root.'/api/';
}else
if (is_dir ( '../api/api' )) {
	$classDir = '../api/api/';
}elseif (is_dir ( '../api' )) {
	$classDir = '../api/';
} 
// $classDir = $root.'/api/';
if (( $dh = opendir ( $classDir ) ) !== false) {
	while ( ( $file = readdir ( $dh ) ) !== false ) {
		// echo "filename: $file : filetype: " . filetype ( $dir . $file ) . "\n";
		$filePath = $classDir . $file;
		if (! in_array ( $file, $exceptFile ) && pathinfo ( $file, PATHINFO_EXTENSION ) == 'php') {
			$className = str_replace ( '.php', '', $file );
// 			echo $filePath;
			require_once $filePath;
			$className = '\\Control\\' . $className;
			$methods = get_class_methods ( $className );
			$classList [$className] = $methods;
		}
	}
	closedir ( $dh );
}else{
	echo 'open failed';
	exit;
}

// foreach ( $classes as $classFile ) {
// $fileName = '../api/' . $classFile;
// if (is_file ( $fileName )) {
// $className = str_replace ( '.php', '', $classFile );
// require_once $fileName;
// $className = '\\Control\\' . $className;
// $methods = get_class_methods ( $className );
// $classList [$className] = $methods;
// }
// }
?>
<!DOCTYPE html>
<html>
<head>
<title>Umesh API Platform</title>
<script src="js/jquery-1.11.0.min.js"></script>
<link href="css/bootstrap.css" rel="stylesheet">
<script src="js/bootstrap.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
	$(function(){
		var urlDefault='<?php echo API_URL?>';
		$('#go').on('click', function(){
			
			var url = $('input[name=url]').val();
			var data = {};
			$('input[name^=argName]').each(function(i){
				var argName = $(this).val();
				var argValue = $('input[name^=argValue]').eq(i).val();
				if(argName != '' && argValue != '')
				data[argName] = argValue;
			});
			$.ajax({
				url: url,
				type: 'post',
				dataType: 'html',
				data: data,
				success: function(response){
					console.log(response);
					$('#result').html(response);
				}
			});
			return false;
		});

		$('.btnMethod').bind('click', function(){
			var className=$(this).attr('p').replace('\\Control\\', '').toLowerCase();
			var methodName=$(this).attr('m').toLowerCase();
			$('input[name=url]').val(urlDefault+'/'+className+'/'+methodName);
		})
	})
</script>
<style>
.methods {
	/*     	float:left; */
	/*     	margin-right:30px; */
	
}
</style>
</head>
<body>
	<div class="container">

		<h2 class="form-signin-heading">API TEST</h2>
		<input name="url" type="text" class="input-block-level" placeholder="REQUEST URL" value="<?php echo API_URL?>" />
		<div class="col-md-12"">
				<?php foreach ($classList as $className => $classMethods){?>
				<ol>
					<?php echo $className;?>
					<?php foreach ($classMethods as $method){?>
						<li class="methods"><button class="btn btn-small btnMethod" p="<?php echo $className?>" m="<?php echo $method?>"><?php echo $method?></button></li>
					<?php }?>
				</ol>
				<?php }?>
			</div>
		<br />
            <?php for($i=1;$i<=12;$i++){?>
            <input name="argName[]" type="text" class="input" placeholder="arg name" />
		<input name="argValue[]" type="text" class="input" placeholder="arg value" />
		<br />
            <?php }?>
            <button class="btn btn-large btn-primary" id="go">Submit</button>
		<div class="col-md-12" id="result">
		<?php //echo print_r($methods);?>
		</div>
		<div class="col-md-12" id="result">
		<?php //echo '<pre>';print_r(json_decode($methods));echo '</pre>';?>
		</div>
	</div>

</body>
</html>