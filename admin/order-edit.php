<?php
use Routine\Db;
require_once ( "inc/init.php" );
require_once ( "inc/config.ui.php" );
Routine\Permission::checkPermission ( 'EOD', true );
$page_title = "訂單管理";

$page_css [] = "fileupload.css";
include ( "inc/header.php" );

$page_nav ["order"]  ['active'] = true;
include ( "inc/nav.php" );
$config = array (
		'pk' => 'order_id',
		'table' => 'av_order',
		'backUrl' => 'order-list.php' 
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
				'catagories_id',
				'brand_id',
				'name',
				'name_en',
				'sn',
				'price',
				'short_desc',
				'online_date',
				'classfy1',
				'classfy2',
				'classfy3',
				'html1',
				'html2',
				'html3' 
		);
		if (empty ( $pk )) {
			
			$sql = 'insert into ' . $config ['table'];
			$sql .= '(' . $db->getFieldsSeparateByDot ( $fields ) . ', add_date, add_account) values';
			$sql .= '(' . $db->getColonFieldsSeparateByDot ( $fields ) . ', NOW(), :add_account)';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $fields, $_POST );
			$bound ['add_account'] = $_SESSION [WebCode . 'bkaccount'];
			
			if ($std->execute ( $bound )) {
				$pk = $db->lastInsertId ();
				
				$mkDir = MediaAbs . '/images/order/' . $pk;
				if (! is_dir ( $mkDir )) {
					mkdir ( $mkDir, 0777 );
				}
				
				Routine\FileField::uploadBackendFiles ( Routine\FileField::$orderFiles, $config ['table'], $config ['pk'], $pk );
				msg ( '新增成功', null, $config ['backUrl'] );
			} else {
				msg ( '失敗', null, $config ['backUrl'] );
			}
		} else {
			$sql = 'update ' . $config ['table'] . ' set ' . $db->getUpdateColon ( $fields ) . ', edit_date = NOW(), edit_account = :edit_account where ' . $config ['pk'] . ' = :pk';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $fields, $_POST );
			$bound ['pk'] = $pk;
			$bound ['edit_account'] = $_SESSION [WebCode . 'bkaccount'];
			Routine\FileField::uploadBackendFiles ( Routine\FileField::$orderFiles, $config ['table'], $config ['pk'], $pk );
			
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
			$filePath = order_abs . '/' . $fileName;
			@unlink ( $filePath );
		}
		go_to ( $_SERVER ['PHP_SELF'] . '?' . $config ['pk'] . '=' . $pk );
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
	$orderDetail = Web\Orders::getOrdersDetail($pk);
	$orderPromotion = Web\Orders::getOrdersPromotion($pk);
}else{
	$row=array();
}

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<script>
var order_id = '<?php echo $pk?>';
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
					<div class="jarviswidget" id="order-edit-basic" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>訂單基本資料管理</h2>

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
												<label class="label">訂單編號</label> <label class="input"> 
												<?php echo $row['order_id']?>
												</label>
											</section>
											<section class="col col-xs-12 col-sm-6">
												<label class="label">訂購者</label> <label class="input"> 
												<?php echo $row['name']?>
												</label>
											</section>
											<section class="col col-xs-12 col-sm-6">
												<label class="label">收件者</label> <label class="input"> <input type="text" name="invoice_name" class="input" value="<?php echo $row['"invoice_name"']?>">
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
											<section class="col col-xs-6 col-sm-3">
												<label class="label">總金額 =</label>
												<label class="label"><?php echo $row['total_price']?></label>
											</section>
											<section class="col col-xs-6 col-sm-3">
												<label class="label">運費 +</label>
												<label class="label"><?php echo $row['freight_price']?></label>
											</section>
											<section class="col col-xs-6 col-sm-3">
												<label class="label">商品小計 -</label>
												<label class="label"><?php echo $row['cart_subtotal_price']?></label>
											</section>
											<section class="col col-xs-6 col-sm-3">
												<label class="label">折扣金額</label>
												<label class="label"><?php echo $row['promotion_discount_price']?></label>
											</section>
										</div>
									</fieldset>
									<fieldset>
										<div class="row">
											<section class="col col-xs-12 col-sm-6">
											<label class="label">收件時段</label> 
												<label class="select">
												<select name="delivery_period">
												<?php foreach (Web\Orders::$deliveryTimes as $delivery_id => $delivery_row){?>
													<option value="<?php echo $delivery_id?>" <?php if($row['delivery_period']==$delivery_id) echo 'selected';?>><?php echo $delivery_row['name']?></option>
												<?php }?>
												</select> <i></i> </label>
											</section>
											<section class="col col-xs-12 col-sm-6">
											<label class="label">貨運方式</label> 
												<label class="select">
												<select name="deliver_method">
												<?php foreach (Web\Orders::$shippingTypes as $delivery_id => $delivery_row){?>
													<option value="<?php echo $delivery_id?>" <?php if($row['deliver_method']==$delivery_id) echo 'selected';?>><?php echo $delivery_row['name']?></option>
												<?php }?>
												</select> <i></i> </label>
											</section>
											<section class="col col-xs-12 col-sm-6">
											<label class="label">貨運方式</label> 
												<label class="select">
												<select name="deliver_method">
												<?php foreach (Web\Orders::$shippingTypes as $delivery_id => $delivery_row){?>
													<option value="<?php echo $delivery_id?>" <?php if($row['deliver_method']==$delivery_id) echo 'selected';?>><?php echo $delivery_row['name']?></option>
												<?php }?>
												</select> <i></i> </label>
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
					<div class="jarviswidget" id="order-product-list" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>訂單細目</h2>
						</header>
						<div>
							<div class="jarviswidget-editbox"></div>
							<div class="widget-body no-padding">
								<div class="widget-body-toolbar"></div>

								<table id="dt_basic" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>產品名稱</th>
											<th>顏色</th>
											<th>單價</th>
											<th>數量</th>
											<th>小計</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($orderDetail as $order_row):?>
										<tr>
											<td><?php echo $order_row['fullname']?></td>
											<td><?php echo $order_row['color_name']?></td>
											<td><?php echo $order_row['price']?></td>
											<td><?php echo $order_row['qty']?></td>
											<td><?php echo $order_row['subtotal_price']?></td>
											<td></td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</article>
				<article class="col-sm-12 col-md-12 col-lg-6">
					<div class="jarviswidget" id="order-promotion-list" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>訂單細目</h2>
						</header>
						<div>
							<div class="jarviswidget-editbox"></div>
							<div class="widget-body no-padding">
								<div class="widget-body-toolbar"></div>

								<table id="dt_basic2" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>促銷名稱</th>
											<th>數量</th>
											<th>折扣單價</th>
											<th>折扣金額</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($orderPromotion as $order_row):?>
										<tr>
											<td><?php echo $order_row['name']?></td>
											<td><?php echo $order_row['qty']?></td>
											<td><?php echo $order_row['discount_unit_price']?></td>
											<td><?php echo $order_row['discount_price']?></td>
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