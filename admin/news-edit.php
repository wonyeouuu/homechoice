<?php
use Routine\Db;
require_once ( "inc/init.php" );
require_once ( "inc/config.ui.php" );
Routine\Permission::checkPermission ( 'ENW', true );
$page_title = "最新消息管理";

$page_css [] = "fileupload.css";
$page_css [] = "product-color.css";
include ( "inc/header.php" );

$page_nav ["news"] ['active'] = true;
include ( "inc/nav.php" );
$config = array (
		'pk' => 'news_id',
		'table' => 'av_news',
		'backUrl' => 'news-list.php' 
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
	case 'modifyColorStock':
		if(is_array($_POST['stock'])){
			foreach ($_POST['stock'] as $colortable_id => $stock){
				$sql = 'update av_product_color set stock = ? where product_id = ? and colortable_id = ?';
				$std=$db->prepare($sql);
				$bound=array($stock, $pk, $colortable_id);
				$std->execute($bound);
				
			}
		}
		go_to('product-edit.php?product_id='.$pk.'');
		print_r($_POST);
		exit;
		break;
	case 'isSubmit' :
		$fields = array (
				'title',
				'content',
				'date'
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
				
				$mkDir = MediaAbs . '/images/product/' . $pk;
				if (! is_dir ( $mkDir )) {
					mkdir ( $mkDir, 0777 );
				}
				
				Routine\FileField::uploadBackendFiles ( Routine\FileField::$newsFiles, $config ['table'], $config ['pk'], $pk );
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
			Routine\FileField::uploadBackendFiles ( Routine\FileField::$newsFiles, $config ['table'], $config ['pk'], $pk );
			
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
}else{
	$row=array();
}

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<script>
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
					<div class="jarviswidget" id="product-edit-basic" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>最新消息基本資料管理</h2>

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
											<section class="col col-6">
												<label class="label">標題</label> <label class="input"> <input type="text" name="title" class="input" value="<?php echo $row['title']?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">上線日期(前台排序由新到舊)</label> <label class="input"> <input type="text" name="date" class="datepicker" value="<?php echo $row['date']?>">
												</label>
											</section>
										</div>
										<div class="row">
											<section class="col col-xs-12">
												<label class="label">簡述</label> <label class="textarea textarea-resizable"> 
												<textarea rows="5" cols="" name="content"  class="ckeditor"><?php echo $row['content']?></textarea>
												</label>
											</section>
										</div>
									</fieldset>
									<fieldset>
										<div class="row">
											<section class="col col-xs-6">
												<label class="label">最新消息圖</label> <label class="input"> <input type="file" name="image" class="input" />
												</label>
												<?php if(\Routine\FileField::isExist($row['image'], 'news', $row)):?>
												<button type="button" class="btn btn-warning btn-xs" style="float: right; margin-top: 100px;" onclick="delete_pic('<?php echo $pk?>', '<?php echo $row['image']?>', this);">刪除</button>
												<?php endif;?>
												
											</section>
											<section class="col col-xs-6">
												<?php if(\Routine\FileField::isExist($row['image'], 'news', $row)):?>
												<img width="200" src="<?php echo Routine\FileField::getImageRel($row['image'], 'news', $row);?>" />
												<?php endif;?>
											</section>
										</div>
									</fieldset>
									<fieldset>
										<div class="row">
											<section class="col col-3">
												<label class="label">建立時間 </label>
											</section>
											<section class="col col-3">
												<label class="label">建立者 </label>
											</section>
											<section class="col col-3">
												<label class="label">最後修改時間 </label>
											</section>
											<section class="col col-3">
												<label class="label">最後修改者</label>
											</section>
										</div>
										<div class="row">
											<section class="col col-3">
												<label class="label"><?php echo $row['add_date']?> </label>
											</section>
											<section class="col col-3">
												<label class="label"><?php echo $row['add_account']?></label>
											</section>
											<section class="col col-3">
												<label class="label"><?php echo $row['edit_date']?></label>
											</section>
											<section class="col col-3">
												<label class="label"><?php echo $row['edit_account']?></label>
											</section>
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
// 		$('#product-accessory-table').dataTable({
// 			"sPaginationType" : "bootstrap_full",
// 			stateSave: true
// 		});
		$('.summernote').summernote({
			height : 180,
			focus : false,
			tabsize : 2
		});
		$('.datepicker').datepicker({dateFormat: 'yy/mm/dd'});
		<?php if (! empty ( $pk )) :?>
		<?php endif;?>
		// PAGE RELATED SCRIPTS
	})

</script>

<?php
// include footer
include ( "inc/footer.php" );
?>