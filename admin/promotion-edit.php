<?php
use Routine\Db;
require_once ( "inc/init.php" );
require_once ( "inc/config.ui.php" );
Routine\Permission::checkPermission ( 'EPM', true );
$page_title = "行銷管理";

$page_css [] = "fileupload.css";
include ( "inc/header.php" );

$page_nav ["promotion"] ['sub'] ['promotion-list'] ['active'] = true;
include ( "inc/nav.php" );
$config = array (
		'pk' => 'promotion_id',
		'table' => 'av_promotion',
		'backUrl' => 'promotion-list.php' 
);

$pk = ( int ) $_REQUEST [$config ['pk']];
$type = ( int ) $_REQUEST ['type'];
$db = Db::getInstance ();
if (! empty ( $pk )) {
}
$permissionSetting = \Routine\Permission::$permissions;
switch ($_REQUEST ['action']) {
	case 'isSubmit' :
		$fields = array (
				'name',
				'type',
				'start_date',
				'end_date',
				'product_qty',
				'product_id',
				'product_id2',
				'discount_type',
				'discount_amount' 
		);
		
		if(empty($_POST['product_qty'])){
			$_POST['product_qty'] = 1;
		}
		$_POST['product_id2'] = (int)$_POST['product_id2'];
		$_POST['discount_type'] = (int)$_POST['discount_type'];
// 		exit;
		if (empty ( $pk )) {
			
			$sql = 'insert into ' . $config ['table'];
			$sql .= '(' . $db->getFieldsSeparateByDot ( $fields ) . ', add_date, add_account) values';
			$sql .= '(' . $db->getColonFieldsSeparateByDot ( $fields ) . ', NOW(), :add_account)';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $fields, $_POST );
			$bound ['add_account'] = $_SESSION [WebCode . 'bkaccount'];
			
			if ($std->execute ( $bound )) {
				$pk = $db->lastInsertId ();
				
				msg ( '新增成功', null, $_SERVER ['PHP_SELF'].'?'.$config['pk'].'='.$pk);
			} else {
				msg ( '失敗', null, $config ['backUrl'] );
			}
		} else {
			$sql = 'update ' . $config ['table'] . ' set ' . $db->getUpdateColon ( $fields ) . ', edit_date = NOW(), edit_account = :edit_account where ' . $config ['pk'] . ' = :pk';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $fields, $_POST );
			$bound ['pk'] = $pk;
			$bound ['edit_account'] = $_SESSION [WebCode . 'bkaccount'];
			if ($std->execute ( $bound )) {
				msg ( '修改成功', null, $config ['backUrl'] );
			} else {
				msg ( '修改失敗', null, $config ['backUrl'] );
			}
		}
		exit ();
		break;
	case 'add_product' :
		$sql = 'insert ignore into av_promotion_product (`promotion_id`, `product_id`) values (?, ?)';
		$std = $db->prepare ( $sql );
		$std->bindValue ( 1, $pk );
		$std->bindValue ( 2, $_REQUEST ['product_id'] );
		$std->execute ();
		break;
	case 'delete_product' :
		$sql = 'delete from av_promotion_product where `promotion_id` = ? and `product_id` = ?';
		$std = $db->prepare ( $sql );
		$std->bindValue ( 1, $pk );
		$std->bindValue ( 2, $_REQUEST ['product_id'] );
		$std->execute ();
		break;
}
if ($pk) {
	
	$sql = 'select a.*, b.name product_name from ' . $config ['table'] . ' a 
			left join av_product b on a.product_id = b.product_id 
			where ' . $config ['pk'] . ' = ?';
	$std = $db->prepare ( $sql );
	$bound = array (
			$pk 
	);
	$std->execute ( $bound );
	$row = $std->fetch ();
	$type = $row ['type'];
	
	if ($row ['type'] == '1') {
		$promotion_product_list = Web\Promotion::getPromotionProductList ( $pk );
		if (empty ( $row ['product_qty'] )) {
			$err_msg = '未填寫件數';
		} elseif (empty ( $row ['discount_amount'] )) {
			$err_msg = '未填寫折數';
		} elseif ($row ['product_qty'] > count ( $promotion_product_list )) {
			$err_msg = '設定件數小於選擇產品件數';
		}
	} elseif ($row ['type'] == '2') {
		$promotion_product_list = Web\Promotion::getPromotionProductList ( $pk );
		if (empty ( $row ['discount_amount'] )) {
			$err_msg = '未填寫折數';
		} elseif (count ( $promotion_product_list ) < 2) {
			$err_msg = '設定件數未滿兩件';
		}
	}
}

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<script>
var promotion_id = '<?php echo $pk?>';
var colortable_id = '<?php echo $colortable_id?>';
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
				<article class="col-sm-12 col-md-12 col-lg-6">
					<div class="jarviswidget" id="promotion-edit-basic" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>行銷基本資料管理</h2>
						</header>
						<div>
							<div class="jarviswidget-editbox"></div>
							<div class="widget-body ">
								<?php if(!empty($err_msg)){?>
								<p class="alert alert-warning">
									<strong><i class="fa fa-warning"></i> 警告</strong> <?php echo $err_msg?> </p>
								<?php }?>

								<form class="" role="form" method="post" enctype="multipart/form-data">
									<fieldset>

										<div class="row">
											<div class="form-group col col-xs-6">
												<label>活動名稱</label> <input type="text" name="name" class="form-control" value="<?php echo $row['name']?>">
											</div>
											<div class="form-group col col-xs-6">
												<label>種類</label> <span class="form-control"><?php echo Web\Promotion::$types[$type]['title']?></span>
											</div>
										</div>
										<div class="row">
											<div class="form-group col col-xs-6">
												<label>開始日期</label>
												<div class="input-group">
													<input type="text" name="start_date" class="datepicker form-control" value="<?php echo $row['start_date']?>"> <span class="input-group-addon"> <i class="fa fa-calendar"
														style="cursor: pointer;"></i>
													</span>
												</div>
											</div>
											<div class="form-group col col-xs-6">
												<label>結束日期</label>
												<div class="input-group">
													<input type="text" name="end_date" class="datepicker form-control" value="<?php echo $row['end_date']?>"> <span class="input-group-addon"> <i class="fa fa-calendar"
														style="cursor: pointer;"></i>
													</span>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group col col-xs-12">
												<label>折扣</label>
												<div class="input-group">
													<?php if($type == '1'){?>
													任選 <input type="text" class="" name="product_qty" size="4" value="<?php echo $row['product_qty']?>" /> 件，可打 <input name="discount_amount" size="6"
														value="<?php echo $row['discount_amount']?>" /> 折
													<?php }?>
													<?php if($type == '2'){?>
													商品 A + B，可打 <input name="discount_amount" size="6" value="<?php echo $row['discount_amount']?>" /> 折
													<?php }?>
													<?php if($type == '3'){?>
													指定 <label id="assign-product-name"><?php echo $row['product_name']?></label> <input name="discount_amount" size="6" value="<?php echo $row['discount_amount']?>" /> 元 (欲折扣金額)
													<input type="hidden" name="product_id" id="product_id" value="<?php echo $row['product_id']?>" />
													<?php }?>
													<?php if($type == '4'){?>
													指定 <label id="assign-product-name"><?php echo $row['product_name']?></label> <input name="discount_amount" size="6" value="<?php echo $row['discount_amount']?>" /> 折
													<input type="hidden" name="product_id" id="product_id" value="<?php echo $row['product_id']?>" />
													<?php }?>
												</div>
											</div>
										</div>
										
										<?php if($type == '2' && false){?>
										<div class="row">
											<section class="col col-xs-6">
												<label>產品A</label>

												<div class="input-group input-group-md">
													<div class="addon-md">
														<input type="text" name="product_id" class="form-control" value="<?php echo $row['start_date'].'產品不拉'?>">
													</div>
													<span class="input-group-btn">
														<button class="btn btn-default btn-info" type="button">選擇產品</button>
													</span>
												</div>
											</section>
											<section class="col col-xs-6">
												<label>產品B</label>

												<div class="input-group input-group-md">
													<div class="addon-md">
														<input type="text" name="product_id2" class="form-control" value="<?php echo $row['start_date'].'產品不拉'?>">
													</div>
													<span class="input-group-btn">
														<button class="btn btn-default btn-info" type="button">選擇產品</button>
													</span>
												</div>
											</section>
										</div>
										<?php }?>
									</fieldset>

									<div class="form-actions">
										<button type="submit" class="btn btn-primary">送出</button>
										<button type="button" class="btn btn-default" onclick="location.href='<?php echo $config['backUrl']?>'">返回</button>
									</div>
									<input type="hidden" name="action" value="isSubmit" /> <input type="hidden" name="<?php echo $config['pk']?>" value="<?php echo $pk;?>" /> <input type="hidden" name="type"
										value="<?php echo $type;?>" />
								</form>

							</div>

						</div>
					</div>
					<?php if(!empty($pk)){?>
					<?php if($type == '1' || $type == '2'){?>
					<div class="jarviswidget" id="promotion-product-list" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>產品列表</h2>
						</header>
						<div>
							<div class="jarviswidget-editbox"></div>
							<div class="widget-body no-padding">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>類別</th>
											<th>產品名稱</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
									
										<?php foreach ($promotion_product_list as $promotion_product_row):?>
										<tr>
											<td><?php echo $promotion_product_row['catagories_name']?></td>
											<td><?php echo $promotion_product_row['name']?></td>
											<td>
												<button class="btn btn-default btn-xs" type="button"
													onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?action=delete_product&product_id=<?php echo $promotion_product_row['product_id']?>&<?php echo $config['pk']?>=<?php echo $pk;?>'">刪除</button>
											</td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<?php }?>
					<?php }?>
				</article>
<?php if(!empty($pk) || $type == '3' || $type == '4'){?>
<?php

	$all_product_list = Web\Product::getAllProductList ();
	?>
				<article class="col-sm-12 col-md-12 col-lg-6">
					<div class="jarviswidget" id="promotion-product-picker" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>產品挑選器</h2>
						</header>
						<div>
							<div class="jarviswidget-editbox"></div>
							<div class="widget-body no-padding">
								<div class="widget-body-toolbar"></div>

								<table id="dt_basic" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>類別</th>
											<th>產品名稱</th>
											<th>品牌</th>
											<th>價格</th>
											<th>操作</th>
										</tr>
									</thead>
<?php
	if ($type == '1') {
		$pickable = true;
	} elseif ($type == '2') {
		if (count ( $promotion_product_list ) < 2) {
			
			$pickable = true;
		} else {
			$pick_err_msg = '已滿';
		}
	}elseif($type == '3'){
$pickidable = true;
}elseif($type == '4'){
$pickidable = true;
}
	?>
									<tbody>
										<?php foreach ($all_product_list as $all_product_row):?>
										<tr>
											<td><?php echo $all_product_row['catagories_name']?></td>
											<td><?php echo $all_product_row['name']?></td>
											<td><?php echo $all_product_row['brand_name']?></td>
											<td><?php echo $all_product_row['price']?></td>
											<td>
												<?php if($pickable == true){?>
													<button class="btn btn-default btn-xs" type="button"
														onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?action=add_product&product_id=<?php echo $all_product_row['product_id']?>&<?php echo $config['pk']?>=<?php echo $pk;?>'">選取</button>
												<?php }elseif(!empty($pick_err_msg)){?>
													<?php echo $pick_err_msg;?>
												<?php }?>
												<?php if($pickidable === true){?>
													<button type="button" class="btn btn-default btn-xs" onclick="pickid(<?php echo $all_product_row['product_id']?>, '<?php echo $all_product_row['name']?>')">選取</button>
												<?php }?>
											</td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</article>
				<article class="col-sm-12 col-md-12 col-lg-6"></article>
<?php }?>
			</div>
		</section>

	</div>

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php
// include required scripts
// include ( "inc/scripts.php" );
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->
<script type="text/javascript" src="../ckeditor/ckeditor/ckeditor.js?88"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables-cust.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/ColReorder.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/FixedColumns.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/ColVis.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/ZeroClipboard.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/media/js/TableTools.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/DT_bootstrap.js"></script>
<script>
function pickid(id, name){
	$('#product_id').val(id);
	$('#assign-product-name').html(name);
}
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