<?php
use Routine\Db;
require_once ( "inc/init.php" );
require_once ( "inc/config.ui.php" );
Routine\Permission::checkPermission ( 'SMB', true );
$page_title = "會員管理";

$page_css [] = "fileupload.css";
include ( "inc/header.php" );

$page_nav ["member"]  ['active'] = true;
include ( "inc/nav.php" );
$config = array (
		'pk' => 'member_id',
		'table' => 'av_member',
		'backUrl' => 'member-list.php' 
);

$pk = ( int ) $_REQUEST [$config ['pk']];
$db = Db::getInstance ();
if (! empty ( $pk )) {
	$colortable_id = ( int ) $_REQUEST ['colortable_id'];
	if (empty ( $colortable_id )) {
		$colortable_id = Web\Product::getFirstColor ( $pk );
	}
}
$permissionSetting = \Routine\Permission::$permissions;
switch ($_REQUEST ['action']) {
	case 'isSubmit' :
		$fields = array (
				'firstname',
				'lastname',
				'gender',
				'email',
				'password',
				'mobile',
				'phone',
				'country',
				'zip',
				'address',
				'level'
		);
		if (empty ( $pk )) {
			
			$sql = 'insert into ' . $config ['table'];
			$sql .= '(' . $db->getFieldsSeparateByDot ( $fields ) . ', add_date) values';
			$sql .= '(' . $db->getColonFieldsSeparateByDot ( $fields ) . ', NOW())';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $fields, $_POST );
			
			if ($std->execute ( $bound )) {
				$pk = $db->lastInsertId ();
				
// 				$mkDir = MediaAbs . '/images/member/' . $pk;
// 				if (! is_dir ( $mkDir )) {
// 					mkdir ( $mkDir, 0777 );
// 				}
				
// 				Routine\FileField::uploadBackendFiles ( Routine\FileField::$memberFiles, $config ['table'], $config ['pk'], $pk );
				msg ( '新增成功', null, $config ['backUrl'] );
			} else {
				msg ( '失敗', null, $config ['backUrl'] );
			}
		} else {
			$sql = 'update ' . $config ['table'] . ' set ' . $db->getUpdateColon ( $fields ) . ' where ' . $config ['pk'] . ' = :pk';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $fields, $_POST );
			$bound ['pk'] = $pk;
// 			Routine\FileField::uploadBackendFiles ( Routine\FileField::$memberFiles, $config ['table'], $config ['pk'], $pk );
			if ($std->execute ( $bound )) {
				msg ( '修改成功', null, $config ['backUrl'] );
			} else {
				msg ( '修改失敗', null, $config ['backUrl'] );
			}
		}
		exit ();
		break;
	case 'deleteFile' :
		$fileName = base64_decode ( $_REQUEST ['file'] );
		$field = $_REQUEST ['field'];
		$sql = 'update ' . $config ['table'] . ' set `' . $field . '` = "" where `' . $config ['pk'] . '` = :pk';
		$std = $db->prepare ( $sql );
		$bound = array (
				'pk' => $pk 
		);
		if ($std->execute ( $bound )) {
			$filePath = member_abs . '/' . $fileName;
			@unlink ( $filePath );
		}
		go_to ( $_SERVER ['PHP_SELF'] . '?' . $config ['pk'] . '=' . $pk );
		break;
	case 'delete_member_color' :
		$sql = 'delete a, b from av_member_color a
				left join av_member_image b on a.member_id = b.member_id and a.colortable_id = b.colortable_id
				where a.member_id = ? and a.colortable_id = ?';
		$std = $db->prepare ( $sql );
		$bound = array (
				$pk,
				$_REQUEST ['colortable_id'] 
		);
		if ($std->execute ( $bound )) {
			$dir = MediaAbs . '/images/member/' . $pk . '/' . $_REQUEST ['colortable_id'];
			if(is_dir($dir)){
			Routine\FileField::delTree ( $dir );
			}
		}
		break;
	case 'add_member_color' :
		$sql = 'insert ignore into av_member_color (`member_id`, `colortable_id`) values (?, ?)';
		$std = $db->prepare ( $sql );
		$bound = array (
				$pk,
				$_REQUEST ['colortable_id'] 
		);
		if ($std->execute ( $bound )) {
			$dir = MediaAbs . '/images/member/' . $pk . '/' . $_REQUEST ['colortable_id'];
			@mkdir ( $dir, 0777, true );
		}
		
		break;
	case 'add_member_accessory' :
		$accessory_id = $_REQUEST ['accessory_id'];
		$sort_number = \Web\Product::getProductAccessoryMaxSort ( $pk );
		$sql = 'insert ignore into av_member_accessory (`member_id`, `accessory_id`, `sort_number`) 
				values (?, ?, ?)';
		$std = $db->prepare ( $sql );
		$bound = array (
				$pk,
				$accessory_id,
				$sort_number + 1 
		);
		$std->execute ( $bound );
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
	$memberOrderList=Web\Member::getOrderList($pk);
}else{
	$row=array();
}

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<script>
var member_id = '<?php echo $pk?>';
</script>
<style>
</style>
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

			<div class="row">

				<!-- NEW COL START -->
				<article class="col-sm-12 col-md-12 col-lg-6">

					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="member-edit-basic" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>會員基本資料管理</h2>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->
<?php

?>
							<!-- widget content -->
							<div class="widget-body no-padding">

								<form class="smart-form" method="post" enctype="multipart/form-data">
									<footer>
										<button type="submit" class="btn btn-primary">送出</button>
										<button type="button" class="btn btn-default" onclick="location.href='<?php echo $config['backUrl']?>'">返回</button>
									</footer>
									<fieldset>
										<div class="row">
											<section class="col col-xs-12 col-sm-6">
												<label class="label">First Name</label> <label class="input"> <input type="text" name="firstname" class="input" value="<?php echo $row['firstname']?>">
												</label>
											</section>
											<section class="col col-xs-12 col-sm-6">
												<label class="label">Last Name</label> <label class="input"> <input type="text" name="lastname" class="input" value="<?php echo $row['lastname']?>">
												</label>
											</section>
											<section class="col col-xs-12 col-sm-6">
												<label class="label">性別</label> 
												<div class="inline-group">
												<label class="radio">
													<input type="radio" name="gender" <?php if($row['gender']=='1') echo 'checked';?>>
													<i></i>男</label>
												<label class="radio">
													<input type="radio" name="gender" <?php if($row['gender']=='2') echo 'checked';?>>
													<i></i>女</label>
											</div>
											</section>
											<section class="col col-xs-12 col-sm-6">
												<label class="label">會員等級</label>
												<div class="inline-group">
													<?php foreach(Web\Member::$levels as $levelNo=>$levelRow){?>
													<label class="radio">
														<input type="radio" name="level" value="<?php echo $levelNo?>" <?php if($row['level']==$levelNo) echo 'checked';?>>
														<i></i><?php echo $levelRow['name']?></label>
													<?php }?>
												</div>
											</section>
											<section class="col col-xs-12 col-sm-6">
												<label class="label">Email</label> <label class="input"> <input type="text" name="email"  value="<?php echo $row['email']?>">
												</label>
											</section>
											<section class="col col-xs-12 col-sm-6">
												<label class="label">密碼</label> <label class="input"> <input type="text" name="password" value="<?php echo $row['password']?>">
												</label>
											</section>
											<section class="col col-xs-12 col-sm-6">
												<label class="label">行動</label> <label class="input"> <input type="text" name="mobile"  value="<?php echo $row['mobile']?>">
												</label>
											</section>
											<section class="col col-xs-12 col-sm-6">
												<label class="label">電話</label> <label class="input"> <input type="text" name="phone" value="<?php echo $row['phone']?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">國家</label> 
												<label class="select">
												<select name="country">
												<?php foreach (Web\Member::$countries as $country_row){?>
													<option value="<?php echo $country_row['id']?>" <?php if($row['country']==$country_row['id']) echo 'selected';?>><?php echo $country_row['name']?></option>
												<?php }?>
												</select> <i></i> </label>
											</section>
											<section class="col col-xs-12 col-sm-6">
												<label class="label">ZIP</label> 
												<label class="input"> <input type="text" name="zip" value="<?php echo $row['zip']?>">
												</label>
											</section>
											<section class="col col-xs-12">
												<label class="label">地址</label> <label class="input"> <input type="text" name="address" value="<?php echo $row['address']?>">
												</label>
											</section>
										</div>
									</fieldset>
									<fieldset>
										<div class="row">
											<section class="col col-3">
												<label class="label">建立時間 </label>
											</section>
										</div>
										<div class="row">
											<section class="col col-3">
												<label class="label"><?php echo $row['add_date']?> </label>
										</div>
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
<?php
if (! empty ( $pk )) :
	?>
				<article class="col-sm-12 col-md-12 col-lg-6">
					<div class="jarviswidget" id="member-order-list" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>會員歷史訂單</h2>
						</header>
						<div>
							<div class="jarviswidget-editbox"></div>
							<div class="widget-body no-padding">
								<div class="widget-body-toolbar"></div>

								<table id="dt_basic" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>訂單編號</th>
											<th>訂購者</th>
											<th>收件者</th>
											<th>訂單狀態</th>
											<th>付款狀態</th>
											<th>總金額</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($memberOrderList as $order_row):?>
										<?php $order_row = Web\Orders::convertOrdersInfor($order_row);?>
										<tr>
											<td><?php echo $order_row['order_id']?></td>
											<td><?php echo $order_row['name']?></td>
											<td><?php echo $order_row['invoice_name']?></td>
											<td><?php echo $order_row['status_text']?></td>
											<td><?php echo $order_row['payment_status_text']?></td>
											<td><?php echo $order_row['total_price']?></td>
											<td></td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</article>
<?php endif;?>
				<!-- END COL -->

			</div>



			<!-- END ROW -->


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
<script src="<?php echo ASSETS_URL; ?>/js/plugin/colorpicker/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/summernote/summernote.js"></script>
<script src="js/member.js"></script>
<script type="text/javascript" src="../ckeditor/ckeditor/ckeditor.js?88"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables-cust.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/ColReorder.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/FixedColumns.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/ColVis.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/ZeroClipboard.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/media/js/TableTools.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/DT_bootstrap.js"></script>
<script>
$(document).ready(function() {
	$('#dt_basic').dataTable({
		"sPaginationType" : "bootstrap_full",
		"oLanguage": {
			"sProcessing":   "處理中...",
		    "sLengthMenu":   " _MENU_ ",
		    "sZeroRecords":  "沒有匹配結果",
		    "sInfo":         "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
		    "sInfoEmpty":    "顯示第 0 至 0 項結果，共 0 項",
		    "sInfoFiltered": "(從 _MAX_ 項結果過濾)",
		    "sInfoPostFix":  "",
		    "sSearch":       "",
		    "sUrl":          "",
		    "oPaginate": {
		        "sFirst":    "首頁",
		        "sPrevious": "上頁",
		        "sNext":     "下頁",
		        "sLast":     "尾頁"
		    }
		}
	});
})

</script>

<?php
// include footer
include ( "inc/footer.php" );
?>