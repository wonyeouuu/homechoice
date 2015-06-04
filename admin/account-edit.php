<?php
use Routine\Db;
require_once ( "inc/init.php" );
require_once ( "inc/config.ui.php" );
Routine\Permission::checkPermission('EAC', true);
$page_title = "後台帳號列表";

$page_css [] = "your_style.css";
include ( "inc/header.php" );

$page_nav ["account"] ["active"] = true;
include ( "inc/nav.php" );
$config = array (
		'pk' => 'account_id',
		'table' => 'lp_account',
		'backUrl' => 'account-list.php' 
);

$pk = ( int ) $_REQUEST [$config ['pk']];
$db = Db::getInstance ();
$permissionSetting = \Routine\Permission::$permissions;
switch ($_REQUEST ['action']) {
	case 'isSubmit' :
		$fields = array (
				'account',
				'name',
				'email',
				'password',
				'status',
				'permission' 
		);
		$_POST ['permission'] = implode ( ',', $_POST ['permission'] );
		if (empty ( $pk )) {
			
			$sql = 'insert into ' . $config ['table'];
			$sql .= '(' . $db->getFieldsSeparateByDot ( $fields ) . ', `add_date`, `add_account`) values';
			$sql .= '(' . $db->getColonFieldsSeparateByDot ( $fields ) . ', NOW(), :bkaccount)';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $fields, $_POST );
			$bound ['bkaccount'] = $_SESSION [WebCode . 'bkaccount'];
			if ($std->execute ( $bound )) {
				msg ( '新增成功', null, $config ['backUrl'] );
			} else {
				msg ( '失敗', null, $config ['backUrl'] );
			}
		} else {
			$sql = 'update ' . $config ['table'] . ' set ' . $db->getUpdateColon ( $fields ) . ', edit_date = NOW(), edit_account = :bkaccount where ' . $config ['pk'] . ' = :pk';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $fields, $_POST );
			$bound ['bkaccount'] = $_SESSION [WebCode . 'bkaccount'];
			$bound ['pk'] = $pk;
			if ($std->execute ( $bound )) {
				msg ( '修改成功', null, $config ['backUrl'] );
			} else {
				msg ( '修改失敗', null, $config ['backUrl'] );
			}
		}
		exit ();
		break;
}

if ($pk) {
	
	$sql = 'select * from ' . $config ['table'] . ' where ' . $config ['pk'] . ' = ?';
	$std = $db->prepare ( $sql );
	$bound = array (
			$pk 
	);
	$std->execute ( $bound );
	$row = $std->fetch ();
	$row ['permissionArray'] = \Routine\Permission::explodeSessionToArray ( $row ['permission'] );
}

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
	// configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
	// $breadcrumbs["New Crumb"] => "http://url.com"
	// $breadcrumbs ["Forms"] = "";
	include ( "inc/ribbon.php" );
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark">
					<i class="fa fa-edit fa-fw "></i> <?php echo $page_title?> <span>> <?php echo $page_title?> </span>
				</h1>
			</div>
		</div>


		<!-- widget grid -->
		<section id="widget-grid" class="">

			<!-- START ROW -->

			<!-- END ROW -->

			<!-- START ROW -->

			<div class="row">

				<!-- NEW COL START -->
				<article class="col-sm-12 col-md-12 col-lg-6">

					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>帳號基本資料管理</h2>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->

							<!-- widget content -->
							<div class="widget-body no-padding">

								<form class="smart-form" method="post" enctype="multipart/form-data">

									<fieldset>
										<div class="row">
											<section class="col col-6">
												<label class="label">帳號</label> <label class="input"> <input type="text" name="account" class="input" value="<?php echo $row['account']?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">姓名</label> <label class="input"> <input type="text" name="name" class="input" value="<?php echo $row['name']?>">
												</label>
											</section>
										</div>
										<div class="row">
											<section class="col col-6">
												<label class="label">密碼</label> <label class="input"> <input type="text" name="password" class="input" value="<?php echo $row['password']?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">Email</label> <label class="input"> <input type="email" name="email" class="email" value="<?php echo $row['email']?>">
												</label>
											</section>
										</div>
										<div class="row">
											<section class="col col-6">
												<div class="inline-group">
													<label class="label">狀態</label> <label class="radio"> <input type="radio" name="status" value="1" <?php if($row['status']=='1') echo 'checked';?>> <i></i>啟用
													</label> <label class="radio"> <input type="radio" name="status" value="2" <?php if($row['status']=='2') echo 'checked';?> /> <i></i>停用
													</label>
												</div>
											</section>
										</div>
									</fieldset>
									<header>權限設定</header>
									<fieldset>
										<?php foreach ($permissionSetting as $priCode => $priRow):?>
										<div class="row" style="margin-bottom: 5px;">
											<div class="inline-group col">
											
											<label class="label"><button class="btn btn-info btn-xs" type="button" onclick="check_all(this);">全勾</button> <?php echo $priRow['title']?> </label> 
												<?php if(isset($priRow['sub']) && is_array($priRow['sub'])):?>
												<?php foreach ($priRow['sub'] as $subIndex=>$subRow):?>
												<label class="checkbox"> <input type="checkbox" name="permission[]" value="<?php echo $subRow['code']?>" <?php if(is_array($row['permissionArray']) && in_array($subRow['code'], $row['permissionArray'])) echo 'checked';?>> <i></i><?php echo $subRow['title']?>
												</label>
												<?php endforeach;?>
												<?php endif;?>
											</div>
										</div>
										<hr />
										<?php endforeach;?>


									</fieldset>


									<footer>
										<button type="submit" class="btn btn-primary">送出</button>
										<button type="button" class="btn btn-default" onclick="location.href='<?php echo $config['backUrl']?>'">返回</button>
									</footer>
									<input type="hidden" name="action" value="isSubmit" />
									<input type="hidden" name="<?php echo $config['pk']?>" value="<?php echo $pk;?>" />
								</form>

							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->

				</article>
				<!-- END COL -->

				<!-- END COL -->

			</div>



			<!-- END ROW -->

			<!-- NEW ROW -->


			<!-- END ROW-->

		</section>
		<!-- end widget grid -->


	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php
// include required scripts
// include ( "inc/scripts.php" );
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script>
function check_all(o){
	var block = $(o).closest('.row');
	var first = $('input[type=checkbox]', block).eq(0).prop('checked');
	if(first){
		$('input[type=checkbox]', block).prop('checked', false);
	}else{
		$('input[type=checkbox]', block).prop('checked', true);
	}
}
</script>

<?php
// include footer
include ( "inc/footer.php" );
?>