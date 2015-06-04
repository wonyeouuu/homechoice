<?php
use Model\Db\Db;
require_once("inc/init.php");
//initilize the page

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");
include("inc/scripts.php");



/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "登入";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
$no_main_header = true;
$page_body_prop = array("id"=>"login", "class"=>"animated fadeInDown");
if(isset($_POST['account'])){
	$db = Db::getInstance();
	
	 $sql = 'select a.* 
			from '.WebCode.'account a
			where a.account = ?
			';
	$std=$db->prepare($sql);
	$bound=array($_POST['account']);
	if($std->execute($bound)){
		$row=$std->fetch();
		if($row['password'] === $_POST['password']){
			echo 'gogogo';
// 			$permissionArr=explode(',', $row['permission']);
			Routine\Permission::registerBackSession($row);
			
			
			go_to('index.php');
			print_r($_SESSION);
			exit;
			
		}else{
			alert('密碼輸入錯誤');
			go_to('login.php');
			exit;
		}
	}else{
		alert('帳號輸入錯誤');
		go_to('login.php');
		exit;
	}
}

include("inc/header.php");




?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
<header id="header">
	<!--<span id="logo"></span>-->

	<div id="logo-group">
		<span id="logo"> <!-- img src="<?php echo ASSETS_URL; ?>/img/logo.png" alt="SmartAdmin"--> </span>

		<!-- END AJAX-DROPDOWN -->
	</div>


</header>

<div id="main" role="main">

	<!-- MAIN CONTENT -->
	<div id="content" class="container">

		<div class="row">
			<div class="col col-xs-12 col-sm-12 col-md-6 col-lg-4 ">
				<div class="well no-padding">
					<form action="<?php echo APP_URL; ?>/login.php" id="login-form" class="smart-form client-form" method="post">
						<header>
							登入
						</header>

						<fieldset>
							
							<section>
								<label class="label">帳號</label>
								<label class="input"> <i class="icon-append fa fa-user"></i>
									<input type="text" name="account">
									<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> 請輸入帳號</b></label>
							</section>

							<section>
								<label class="label">密碼</label>
								<label class="input"> <i class="icon-append fa fa-lock"></i>
									<input type="password" name="password">
									<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> 請輸入密碼</b> </label>
							</section>

						</fieldset>
						<footer>
							<button type="submit" class="btn btn-primary">
								登入
							</button>
						</footer>
					</form>

				</div>
				
				
			</div>
			
		</div>
	</div>

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script type="text/javascript">
// 	runAllForms();

	$(function() {
		// Validation
		$("#login-form").validate({
			// Rules for form validation
			rules : {
				account : {
					required : true
				},
				password : {
					required : true,
					minlength : 3,
					maxlength : 20
				}
			},

			// Messages for form validation
			messages : {
				account : {
					required : '請輸入帳號',
					email : 'Please enter a VALID email address'
				},
				password : {
					required : '請輸入密碼'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
	});
</script>

<?php 
	//include footer
	include("inc/footer.php"); 
?>