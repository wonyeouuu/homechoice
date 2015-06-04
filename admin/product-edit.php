<?php
use Routine\Db;
require_once ( "inc/init.php" );
require_once ( "inc/config.ui.php" );
Routine\Permission::checkPermission ( 'EPR', true );
$page_title = "產品管理";

$page_css [] = "fileupload.css";
$page_css [] = "product-color.css";
include ( "inc/header.php" );

$page_nav ["product"] ['sub'] ['product-list'] ['active'] = true;
include ( "inc/nav.php" );
$config = array (
		'pk' => 'product_id',
		'table' => 'av_product',
		'backUrl' => 'product-list.php' 
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
				'catagories_id',
				'catagories_id2',
				'catagories_id3',
				'brand_id',
				'name',
				'name_en',
				'sn',
				'price',
				'freight_price',
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
				
				$mkDir = MediaAbs . '/images/product/' . $pk;
				if (! is_dir ( $mkDir )) {
					mkdir ( $mkDir, 0777 );
				}
				
				Routine\FileField::uploadBackendFiles ( Routine\FileField::$productFiles, $config ['table'], $config ['pk'], $pk );
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
			Routine\FileField::uploadBackendFiles ( Routine\FileField::$productFiles, $config ['table'], $config ['pk'], $pk );
			
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
			$filePath = product_abs . '/' . $fileName;
			@unlink ( $filePath );
		}
		go_to ( $_SERVER ['PHP_SELF'] . '?' . $config ['pk'] . '=' . $pk );
		break;
	case 'delete_product_color' :
		$sql = 'delete a, b from av_product_color a
				left join av_product_image b on a.product_id = b.product_id and a.colortable_id = b.colortable_id
				where a.product_id = ? and a.colortable_id = ?';
		$std = $db->prepare ( $sql );
		$bound = array (
				$pk,
				$_REQUEST ['colortable_id'] 
		);
		if ($std->execute ( $bound )) {
			$dir = MediaAbs . '/images/product/' . $pk . '/' . $_REQUEST ['colortable_id'];
			if(is_dir($dir)){
			Routine\FileField::delTree ( $dir );
			}
		}
		break;
	case 'add_product_color' :
		$sql = 'insert ignore into av_product_color (`product_id`, `colortable_id`) values (?, ?)';
		$std = $db->prepare ( $sql );
		$bound = array (
				$pk,
				$_REQUEST ['colortable_id'] 
		);
		if ($std->execute ( $bound )) {
			$dir = MediaAbs . '/images/product/' . $pk . '/' . $_REQUEST ['colortable_id'];
			@mkdir ( $dir, 0777, true );
		}
		
		break;
	case 'add_product_accessory' :
		$accessory_id = $_REQUEST ['accessory_id'];
		$sort_number = \Web\Product::getProductAccessoryMaxSort ( $pk );
		$sql = 'insert ignore into av_product_accessory (`product_id`, `accessory_id`, `sort_number`) 
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
$sql = 'select a.catagories_id, name from av_product_catagories a order by sort_number asc';
$std = $db->prepare ( $sql );
$std->execute ();
$cList = array ();
while ( ( $row = $std->fetch () ) !== false ) {
	$cList [] = $row;
}
$sql = 'select a.brand_id, name from av_product_brand a order by sort_number asc';
$std = $db->prepare ( $sql );
$std->execute ();
$bList = array ();
while ( ( $row = $std->fetch () ) !== false ) {
	$bList [] = $row;
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
	$row['html1'] = '<h2 class="td v-top">品牌國家：</h2>
<p class="td v-top"> </p>
<ul class="tr"></ul>
<hr />
<h2 class="td v-top">尺寸：</h2>
<p class="td v-top"> x  x  (單位: CM)</p>
<ul class="tr"></ul>
<hr />
<h2 class="td v-top">使用須知:</h2>
<p class="td v-top">-<br />
	-<br />
	-
</p>
<ul class="tr"></ul>
<hr />';
	$row['html2'] = '<h2 class="td v-top">品牌介紹：</h2>
<p class="td v-top"></p>
<ul class="tr"></ul>
<hr />';
	$row['html3'] = '<h2 class="td v-top">其他:</h2>
<p class="td v-top">-</p>
<ul class="tr"></ul>
<hr />';
}

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<script>
var product_id = '<?php echo $pk?>';
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

				<!-- NEW COL START -->
				<article class="col-sm-12 col-md-12 col-lg-6">

					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="product-edit-basic" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>產品基本資料管理</h2>

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
												<label class="label">類別</label> 
												
												<label class="select"> <select name="catagories_id" class="input-sm">
														<option value="">無</option>
														<?php foreach ($cList as $cRow):?>
														<option value="<?php echo $cRow['catagories_id']?>" <?php if($row['catagories_id']==$cRow['catagories_id']) echo 'selected';?>><?php echo $cRow['name']?></option>
														<?php endforeach;?>
													</select> <i></i>
												</label>
												<label class="select"> <select name="catagories_id2" class="input-sm">
														<option value="">無</option>
														<?php foreach ($cList as $cRow):?>
														<option value="<?php echo $cRow['catagories_id']?>" <?php if($row['catagories_id2']==$cRow['catagories_id']) echo 'selected';?>><?php echo $cRow['name']?></option>
														<?php endforeach;?>
													</select> <i></i>
												</label>
												<label class="select"> <select name="catagories_id3" class="input-sm">
														<option value="">無</option>
														<?php foreach ($cList as $cRow):?>
														<option value="<?php echo $cRow['catagories_id']?>" <?php if($row['catagories_id3']==$cRow['catagories_id']) echo 'selected';?>><?php echo $cRow['name']?></option>
														<?php endforeach;?>
													</select> <i></i>
												</label>
											</section>
											<section class="col col-6">
												<label class="label">品牌</label> <label class="select"> <select name="brand_id" class="input-sm">
														<option value="">無</option>
														<?php foreach ($bList as $cRow):?>
														<option value="<?php echo $cRow['brand_id']?>" <?php if($row['brand_id']==$cRow['brand_id']) echo 'selected';?>><?php echo $cRow['name']?></option>
														<?php endforeach;?>
													</select> <i></i>
												</label>
											</section>
										</div>
										<div class="row">
											<section class="col col-6">
												<label class="label">名稱</label> <label class="input"> <input type="text" name="name" class="input" value="<?php echo $row['name']?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">英文名稱</label> <label class="input"> <input type="text" name="name_en" class="input" value="<?php echo $row['name_en']?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">產品編號</label> 
												<!-- label class="input"> <input type="text" name="sn" class="input" value="<?php echo $row['sn']?>">
												</label-->
												<label class="textarea textarea-resizable"> 
												<textarea rows="5" cols="" name="sn" class="custom-scroll"><?php echo $row['sn']?></textarea>
												</label>
											</section>
											<section class="col col-6">
												<label class="label">上線日期(前台排序由新到舊)</label> <label class="input"> <input type="text" name="online_date" class="datepicker" value="<?php echo $row['online_date']?>">
												</label>
											</section>
										</div>
										<div class="row">
											<section class="col col-5">
												<label class="label">售價</label> <label class="input"> <input type="text" name="price" class="number" value="<?php echo $row['price']?>">
												</label>
											</section>
											<section class="col col-5">
												<label class="label">運費</label> <label class="input"> <input type="text" name="freight_price" class="number" value="<?php echo $row['freight_price']?>">
												</label>
											</section>
										</div>
										<div class="row">
											<section class="col col-xs-12">
												<label class="label">簡述</label> <label class="textarea textarea-resizable"> 
												<textarea rows="5" cols="" name="short_desc" class="custom-scroll"><?php echo $row['short_desc']?></textarea>
												</label>
											</section>
										</div>
									</fieldset>
									<fieldset>
										<div class="row">
											<section class="col">
												<div class="inline-group">
													<label class="label">屬性設定</label> <label class="checkbox"> <input type="checkbox" name="classfy1" value="1" <?php if($row['classfy1']=='1') echo 'checked';?> /> <i></i>新進商品
													</label> <label class="checkbox"> <input type="checkbox" name="classfy3" value="1" <?php if($row['classfy3']=='1') echo 'checked';?> /> <i></i>最佳銷售商品
													</label> <label class="checkbox"> <input type="checkbox" name="classfy2" value="1" <?php if($row['classfy2']=='1') echo 'checked';?> /> <i></i>禮品推薦 [左上角圖示]
													</label>
												</div>
											</section>
										</div>
									</fieldset>
									<script>
function delete_pic(product_id, image, o){
	$.ajax({
		url: 'ajax/ajax.php',
		type: 'post',
		dataType: 'json',
		data: {
			action: 'delete_product_pic',
			product_id: product_id,
			image: image
		},
		success: function(response){
			$(o).closest('section').next().find('img').remove();
			$(o).remove();
		}
	})
}
</script>
									<fieldset>
										<div class="row">
											<section class="col col-xs-6">
												<label class="label">產品預設圖</label> <label class="input"> <input type="file" name="image" class="input" />
												</label>
												<?php if(\Routine\FileField::isExist($row['image'], 'product', $row)):?>
												<button type="button" class="btn btn-warning btn-xs" style="float: right; margin-top: 100px;" onclick="delete_pic('<?php echo $pk?>', '<?php echo $row['image']?>', this);">刪除</button>
												<?php endif;?>
												
											</section>
											<section class="col col-xs-6">
												<?php if(\Routine\FileField::isExist($row['image'], 'product', $row)):?>
												<img src="<?php echo Routine\FileField::getImageRel($row['image'], 'product', $row);?>" />
												<?php endif;?>
											</section>
										</div>
									</fieldset>
									<fieldset>
										<ul id="myTab1" class="nav nav-tabs bordered">
											<li class="active"><a href="#s1" data-toggle="tab">
													商品介紹 
													<?php if(empty($row['html1'])):?>
													<span class="badge bg-color-red txt-color-white">!</span>
													<?php endif;?>
												</a></li>
											<li><a href="#s2" data-toggle="tab">設計師介紹
													<?php if(empty($row['html2'])):?>
													<span class="badge bg-color-red txt-color-white">!</span>
													<?php endif;?>
											</a></li>
											<li><a href="#s3" data-toggle="tab">
													其他補充 
													<?php if(empty($row['html3'])):?>
													<span class="badge bg-color-red txt-color-white">!</span>
													<?php endif;?>
													
												</a></li>
										</ul>
										<div id="myTabContent1" class="tab-content padding-10">
											<div class="tab-pane fade in active" id="s1">
												<textarea name="html1" class="ckeditor"><?php echo $row['html1']?></textarea>
											</div>
											<div class="tab-pane fade" id="s2">
												<textarea name="html2" class="ckeditor"><?php echo $row['html2']?></textarea>
											</div>
											<div class="tab-pane fade" id="s3">
												<textarea name="html3" class="ckeditor"><?php echo $row['html3']?></textarea>
											</div>
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
<?php
if (! empty ( $pk )) :
	?>
				<article class="col-sm-12 col-md-12 col-lg-6">

					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="product-edit-colorpicker" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>產品色彩管理</h2>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->
<?php
	$sql = 'select b.*, a.product_id, a.stock
		from av_product_color a 
		left join av_colortable b on a.colortable_id = b.colortable_id
		where a.product_id = ?';
	$std = $db->prepare ( $sql );
	$bound = array (
			$pk 
	);
	$std->execute ( $bound );
	
	$clList = array ();
	while ( ( $row = $std->fetch () ) !== false ) {
		$row ['show'] = \Web\Colortable::getShow ( $row, 'product-color-block' );
		$clList [] = $row;
	}
	$sql = 'select a.*, b.product_id
		from av_colortable a
		left join av_product_color b on b.product_id = ? and a.colortable_id = b.colortable_id
		where b.product_id is null';
	$std = $db->prepare ( $sql );
	$bound = array (
			$pk 
	);
	$std->execute ( $bound );
	
	$pcList = array ();
	while ( ( $row = $std->fetch () ) !== false ) {
		$row['product_id'] = $pk;
		$row ['show'] = \Web\Colortable::getShow ( $row, 'product-color-block' );
		$pcList [] = $row;
	}
	?>
<style>
.product-color-block {
	float: left;
	margin-right: 20px;
	border: 1px solid #BBB;
	cursor: pointer;
}
</style>
							<!-- widget content -->
							<div class="widget-body no-padding">

								<form class="smart-form" method="post" enctype="multipart/form-data">
								<input type="hidden" name="action" value="modifyColorStock" />
								<input type="hidden" name="product_id" value="<?php echo $pk?>" />
									<header>已有顏色
									<button class="btn btn-info btn-sm" type="submit">修改庫存</button>
									</header>
									<fieldset>
										<div class="row">
											<section class="col col-10 ">
												<?php foreach ($clList as $clRow):?>
													<div class="product-color-wrapper">
													<a href="<?php echo $_SERVER['PHP_SELF']?>?action=delete_product_color&<?php echo $config['pk']?>=<?php echo $pk;?>&colortable_id=<?php echo $clRow['colortable_id']?>" >
													
													<?php echo $clRow['show']?>
													</a>
													<input class="product-color-stock-input" data-product_id="<?php echo $pk?>" data-colortable_id="<?php echo $clRow['colortable_id']?>" name="stock[<?php echo $clRow['colortable_id']?>]" value="<?php echo $clRow['stock']?>" />
													</div>
												<?php endforeach;?>
											</section>
										</div>
									</fieldset>
									<header>挑選色表顏色</header>
									<fieldset>
										<div class="row">
											<section class="col col-10 ">
												<?php foreach ($pcList as $clRow):?>
													<a href="<?php echo $_SERVER['PHP_SELF']?>?action=add_product_color&<?php echo $config['pk']?>=<?php echo $pk;?>&colortable_id=<?php echo $clRow['colortable_id']?>" >
													<?php echo $clRow['show']?>
													</a>
												<?php endforeach;?>
											</section>
										</div>
									</fieldset>


								</form>

							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->

					<?php if(count($clList)>0):?>
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-blueDark" id="product-color-image" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-picture-o"></i>
							</span>
							<h2>產品照片(顏色分類)</h2>

						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->

							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->

							</div>
							<!-- end widget edit box -->

							<!-- widget content -->
							<div class="widget-body ">
								<div class="row">
									<section class="col col-10">
								
								<?php foreach($clList as $pcIndex => $pcRow){?>
								<div class="typeBlock <?php if($pcRow['colortable_id'] == $colortable_id) echo 'nowBlock';?>" <?php if($pcRow['colortable_id'] != $colortable_id) {?>
											onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?<?php echo $config['pk']?>=<?php echo $pk?>&colortable_id=<?php echo $pcRow['colortable_id']?>'" <?php }?>
											id="typeBlock-<?php echo $pcRow['colortable_id']?>"><?php echo $pcRow['name']?></div>
								
								<?php }?> 
								</section>
								</div>
								<div class="row">
									<ul id="album"></ul>

									<div class="addPhotoBtn" id="addPhotoBtn" onclick="$('#addPhotoInput').trigger('click');">+</div>
									<form action="product_ajax.php" method="post" id="uploadPhotoForm" enctype="multipart/form-data">
										<input type="file" name="photo[]" id="addPhotoInput" multiple style="visibility: hidden;" />
										<input type="hidden" name="action" value="uploadPhoto" />
										<input type="hidden" name="<?php echo $config['pk']?>" value="<?php echo $pk?>" />
										<input type="hidden" name="colortable_id" value="<?php echo $colortable_id?>" />
									</form>
								</div>
							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<?php endif;?>
					<div class="jarviswidget jarviswidget-color-blueDark" id="product-accessory" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-coffee"></i>
							</span>
							<h2>加購商品</h2>
						</header>
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox"></div>
							<!-- end widget edit box -->
							<!-- widget content -->
							<div class="widget-body no-padding">
								<div class="widget-body-toolbar smart-form row">
									<div class="col col-4">
										<label class="select"> <select class="select" id="add-accessory-select">
											<?php foreach (Web\Product::getNotProductAccessoryList($pk) as $npaRow):?>
											<option value="<?php echo $npaRow['accessory_id']?>"><?php echo $npaRow['name']?></option>
											<?php endforeach;?>
											</select> <i></i>
										</label>
									</div>
									<button type="button" class="btn btn-sm btn-info" onclick="add_product_accessory(this); ">新增</button>
								</div>
								<div class="widget-body-toolbar"></div>
								<table id="product-accessory-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td>產品名稱</td>
											<td>價格</td>
											<td>縮圖</td>
											<td>操作</td>
										</tr>
									</thead>
									<tbody>
										<?php foreach (Web\Product::getProductAccessoryList($pk) as $paRow):?>
										
										<tr>
											<td><?php echo $paRow['name']?></td>
											<td><?php echo $paRow['price']?></td>
											<td>
												<?php if(!empty($paRow['image_url'])):?>
												<img src="<?php echo $paRow['image_url']?>" style="width: 100px" />
												<?php endif;?>
											</td>
											<td>
												<button type="button" class="btn btn-info btn-sm" onclick="up_product_accessory('<?php echo $pk;?>', '<?php echo $paRow['accessory_id']?>', this)">往上</button>
												<button type="button" class="btn btn-info btn-sm" onclick="down_product_accessory('<?php echo $pk;?>', '<?php echo $paRow['accessory_id']?>', this)">往下</button>
												<button type="button" class="btn btn-warning btn-sm" onclick="delete_product_accessory('<?php echo $pk;?>','<?php echo $paRow['accessory_id']?>', '<?php echo $paRow['name']?>', this);">刪除</button>
											</td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="jarviswidget jarviswidget-color-blueDark" id="product-" data-widget-deletebutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-coffee"></i>
							</span>
							<h2>相關商品</h2>
						</header>
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox"></div>
							<!-- end widget edit box -->
							<!-- widget content -->
							<div class="widget-body no-padding">
								<div class="widget-body-toolbar smart-form row">
									<div class="col col-4">
										<label class="select"> <select class="select" id="add-relative-catagories-select" onchange="change_relative_catagories_select();">
											<?php foreach ($cList as $cRow):?>
											<option value="<?php echo $cRow['catagories_id']?>"><?php echo $cRow['name']?></option>
											<?php endforeach;?>
											<option value="0">無</option>
												<option value="">不拘</option>
											</select> <i></i>
										</label>
									</div>
									<div class="col col-4">
										<label class="select"> <select class="select" id="add-relative-select">
											</select> <i></i>
										</label>
									</div>
									<button type="button" class="btn btn-sm btn-info" onclick="add_product_relative(this); ">新增</button>
								</div>
								<div class="widget-body-toolbar"></div>
								<table id="product-relative-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td>產品名稱</td>
											<td>價格</td>
											<td>操作</td>
										</tr>
									</thead>
									<tbody>
										<?php foreach (Web\Product::getProductRelativeList($pk) as $paRow):?>
										
										<tr>
											<td><a href="product-edit.php?product_id=<?php echo $paRow['product_id']?>"><?php echo $paRow['name']?></a></td>
											<td><?php echo $paRow['price']?></td>
											<td>
												<button type="button" class="btn btn-info btn-sm" onclick="up_product_relative('<?php echo $pk;?>', '<?php echo $paRow['relative_id']?>', this)">往上</button>
												<button type="button" class="btn btn-info btn-sm" onclick="down_product_relative('<?php echo $pk;?>', '<?php echo $paRow['relative_id']?>', this)">往下</button>
												<button type="button" class="btn btn-warning btn-sm" onclick="delete_product_relative('<?php echo $pk;?>','<?php echo $paRow['relative_id']?>', '<?php echo $paRow['name']?>', this);">刪除</button>
											</td>
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
<script src="js/product.js"></script>
<script type="text/javascript" src="../ckeditor/ckeditor/ckeditor.js?88"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/jquery.dataTables-cust.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/ColReorder.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/FixedColumns.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/ColVis.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/ZeroClipboard.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/media/js/TableTools.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/plugin/datatables/DT_bootstrap.js"></script>
<script>
function change_relative_catagories_select(){
	var catagories_id = $('#add-relative-catagories-select').val();
	$.ajax({
		url: 'ajax/ajax.php',
		
		type: 'post',
		dataType: 'json',
		data: {
			catagories_id: catagories_id,
			action : 'change_relative_catagories_select',
		},
		success: function(response){
			if(response.success == 1){
				var html = '';
				for(var i in response.list){
					html += '<option value="'+response.list[i].product_id+'">'+response.list[i].name+'</option>';
				}
				$('#add-relative-select').html(html);
			}
		}
		
	});
}
function down_product_relative(product_id, relative_id, o){
	$.ajax({
		url: 'ajax/ajax.php',
		
		type: 'post',
		dataType: 'json',
		data: {
			product_id: product_id,
			relative_id: relative_id,
			action : 'down_product_relative',
		},
		success: function(response){
			if(response.success == 1){
				var block = $(o).closest('tr');
		         $(block).before($(block).next());  
			}
		}
		
	});
}
function up_product_relative(product_id, relative_id, o){
	$.ajax({
		url: 'ajax/ajax.php',
		
		type: 'post',
		dataType: 'json',
		data: {
			product_id: product_id,
			relative_id: relative_id,
			action : 'up_product_relative',
		},
		success: function(response){
			if(response.success == 1){
				var block = $(o).closest('tr');
		         $(block).after($(block).prev());  
			}
		}
		
	});
}
function down_product_accessory(product_id, accessory_id, o){
	$.ajax({
		url: 'ajax/ajax.php',
		
		type: 'post',
		dataType: 'json',
		data: {
			product_id: product_id,
			accessory_id: accessory_id,
			action : 'down_product_accessory',
		},
		success: function(response){
			if(response.success == 1){
				var block = $(o).closest('tr');
		         $(block).before($(block).next());  
			}
		}
		
	});
}
function up_product_accessory(product_id, accessory_id, o){
	$.ajax({
		url: 'ajax/ajax.php',
		
		type: 'post',
		dataType: 'json',
		data: {
			product_id: product_id,
			accessory_id: accessory_id,
			action : 'up_product_accessory',
		},
		success: function(response){
			if(response.success == 1){
				var block = $(o).closest('tr');
		         $(block).after($(block).prev());  
			}
		}
		
	});
}
function add_product_relative(o){
	var relative_id = $('#add-relative-select').val();
	$.ajax({
		url: 'ajax/ajax.php',
		
		type: 'post',
		dataType: 'json',
		data: {
			product_id: '<?php echo $pk?>',
			relative_id: relative_id,
			action : 'add_product_relative',
		},
		success: function(response){
			if(response.success == 1){
// 				$('#add-accessory-select option[value='+accessory_id+']').remove();
				
				$('#product-relative-table tbody').append(get_product_relative_row_html(response.infor));
			}
		}
		
	});
}
function add_product_accessory(o){
	var accessory_id = $('#add-accessory-select').val();
	$.ajax({
		url: 'ajax/ajax.php',
		
		type: 'post',
		dataType: 'json',
		data: {
			product_id: '<?php echo $pk?>',
			accessory_id: accessory_id,
			action : 'add_product_accessory',
		},
		success: function(response){
			if(response.success == 1){
				$('#add-accessory-select option[value='+accessory_id+']').remove();
				
				$('#product-accessory-table tbody').append(get_product_accessory_row_html(response.infor));
			}
		}
		
	});
}
function get_product_relative_row_html(row){
	var html="";
	html += "<tr>";
	html += "	<td>"+row.name+"<\/td>";
	html += "	<td>"+row.price+"<\/td>";
	html += "	<td>";
	html += "		<button type=\"button\" class=\"btn btn-info btn-sm\" onclick=\"up_product_relative('<?php echo $pk;?>', '"+row.relative_id+"', this)\">往上<\/button>";
	html += "		<button type=\"button\" class=\"btn btn-info btn-sm\" onclick=\"down_product_relative('<?php echo $pk;?>', '"+row.relative_id+"', this)\">往下<\/button>";
	html += "		<button type=\"button\" class=\"btn btn-warning btn-sm\" onclick=\"delete_product_relative('<?php echo $pk;?>','"+row.relative_id+"', '"+row.name+"', this);\">刪除<\/button>";
	html += "	<\/td>";
	html += "<\/tr>";
		return html;
}
function get_product_accessory_row_html(row){
	var html="";
	html += "<tr>";
	html += "	<td>"+row.name+"<\/td>";
	html += "	<td>"+row.price+"<\/td>";
	html += "	<td><img src=\""+row.image_url+"\" style=\"width: 100px\"><\/td>";
	html += "	<td>";
	html += "		<button type=\"button\" class=\"btn btn-info btn-sm\" onclick=\"up_product_accessory('<?php echo $pk;?>', '"+row.accessory_id+"', this)\">往上<\/button>";
	html += "		<button type=\"button\" class=\"btn btn-info btn-sm\" onclick=\"down_product_accessory('<?php echo $pk;?>', '"+row.accessory_id+"', this)\">往下<\/button>";
	html += "		<button type=\"button\" class=\"btn btn-warning btn-sm\" onclick=\"delete_product_accessory('<?php echo $pk;?>','"+row.accessory_id+"', '"+row.name+"', this);\">刪除<\/button>";
	html += "	<\/td>";
	html += "<\/tr>";
		return html;
}
function delete_product_relative(product_id, relative_id, name, o){
	$.ajax({
		url: 'ajax/ajax.php',
		
		type: 'post',
		dataType: 'json',
		data: {
			product_id: product_id,
			relative_id: relative_id,
			action : 'delete_product_relative',
		},
		success: function(response){
			if(response.success == 1){
				$(o).closest('tr').remove();
				$('#add-relative-select').append('<option value="'+relative_id+'">'+name+'</option>');
			}
		}
		
	});
}
function delete_product_accessory(product_id, accessory_id, name, o){
	$.ajax({
		url: 'ajax/ajax.php',
		
		type: 'post',
		dataType: 'json',
		data: {
			product_id: product_id,
			accessory_id: accessory_id,
			action : 'delete_product_accessory',
		},
		success: function(response){
			if(response.success == 1){
				$(o).closest('tr').remove();
				$('#add-accessory-select').append('<option value="'+accessory_id+'">'+name+'</option>');
			}
		}
		
	});
}
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