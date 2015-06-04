<?php
use Routine\Db;
require_once ( "inc/init.php" );
require_once ( "inc/config.ui.php" );
Routine\Permission::checkPermission ( 'EOD', true );
$page_title = "特店管理";

$page_css [] = "fileupload.css";
$page_css [] = "product-color.css";
include ( "inc/header.php" );

$page_nav ["merchant"] ['active'] = true;
include ( "inc/nav.php" );
$config = array (
		'pk' => 'mId',
		'table' => 'merchant',
		'backUrl' => 'merchant-list.php' 
);

$pk = ( int ) $_REQUEST [$config ['pk']];
$db = Db::getInstance ();
if (! empty ( $pk )) {
}
function getGeo($addr){
	$url = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=true&language=zh-TW&region=tw&address=' . urlencode ( $addr );
	$geo = file_get_contents ( $url );
	$res = json_decode ( $geo, true );
	if ($res ['status'] == 'OK') {
		return   array (
				'latitude' => $res ['results'] [0] ['geometry'] ['location'] ['lat'] ,
				'longitude' => $res ['results'] [0] ['geometry'] ['location'] ['lng'] ,
				'success'=>'1'
		 );
	}else{
		return false;
	}
}
$permissionSetting = \Routine\Permission::$permissions;
switch ($_REQUEST ['action']) {
	case 'editLocation' :
		$mlId = ( int ) $_REQUEST ['mlId'];
		if(!empty($_REQUEST['address'])){
			if($geos=getGeo($_REQUEST['address'])){
				$_REQUEST['lantitude'] = $geos['latitude'];
				$_REQUEST['longitude'] = $geos['longitude'];
			}
			
		}
		if ($mlId == 0) {
			$sql = 'insert into merchant_location (`mId`, `name`, `lantitude`, `longitude`, `address`) values (:mId, :name, :lantitude, :longitude, :address)';
			$std = $db->prepare ( $sql );
			$bound = array (
					'mId' => $_REQUEST ['mId'] ,
					'name' => $_REQUEST ['name'] ,
					'lantitude' => $_REQUEST ['lantitude'] ,
					'longitude' => $_REQUEST ['longitude'] ,
					'address' => $_REQUEST ['address'] ,
			);
			$std->execute($bound);
		}else{
			$sql = 'update merchant_location set name = :name, lantitude = :lantitude, longitude = :longitude, address = :address where mlId = :mlId';
			$std = $db->prepare ( $sql );
			$bound = array (
					'mlId' => $_REQUEST ['mlId'] ,
					'name' => $_REQUEST ['name'] ,
					'lantitude' => $_REQUEST ['lantitude'] ,
					'longitude' => $_REQUEST ['longitude'] ,
					'address' => $_REQUEST ['address'] ,
			);
			$std->execute($bound);
		}
		go_to($_SERVER['PHP_SELF']."?mId=".$pk);
		break;
	case 'deleteLocation':
		$sql = 'delete from merchant_location where mlId = ?';
		$std=$db->prepare($sql);
		$bound=array($_REQUEST['mlId']);
		$std->execute($bound);
		go_to($_SERVER['PHP_SELF']."?mId=".$pk);
		break;
	case 'isSubmit' :
		$fields = array (
				'cId',
				'name',
				'content',
				'startDate',
				'endDate',
				'desc',
				'status',
				'slogan' 
		);
		if (empty ( $pk )) {
			
			$sql = 'insert into ' . $config ['table'];
			$sql .= '(' . $db->getFieldsSeparateByDot ( $fields ) . ', addDate, adder) values';
			$sql .= '(' . $db->getColonFieldsSeparateByDot ( $fields ) . ', NOW(), :add_account)';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $fields, $_POST );
			$bound ['add_account'] = $_SESSION [WebCode . 'bkaccount'];
			
			if ($std->execute ( $bound )) {
				$pk = $db->lastInsertId ();
				
				$citys=$_POST['citys'];
				foreach ($citys as $cityId){
					$sql = 'replace into '.$config['table'].'_city_map (`'.$config['pk'].'`, `cityId`) values (?, ?)';
					$std=$db->prepare($sql);
					$bound=array($pk, $cityId);
					$std->execute($bound);
				}
				// $mkDir = MediaAbs . '/images/product/' . $pk;
				// if (! is_dir ( $mkDir )) {
				// mkdir ( $mkDir, 0777 );
				// }
				
				// Routine\FileField::uploadBackendFiles ( Routine\FileField::$newsFiles, $config ['table'], $config ['pk'], $pk );
				msg ( '新增成功', null, $config ['backUrl'] );
			} else {
				msg ( '失敗', null, $config ['backUrl'] );
			}
		} else {
			$sql = 'update ' . $config ['table'] . ' set ' . $db->getUpdateColon ( $fields ) . ', editDate = NOW(), editor = :edit_account where ' . $config ['pk'] . ' = :pk';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $fields, $_POST );
			$bound ['pk'] = $pk;
			$bound ['edit_account'] = $_SESSION [WebCode . 'bkaccount'];
			// Routine\FileField::uploadBackendFiles ( Routine\FileField::$newsFiles, $config ['table'], $config ['pk'], $pk );
			
			if ($std->execute ( $bound )) {
				
				$citys=$_POST['citys'];
				$sql = 'delete from '.$config['table'].'_city_map where '.$config['pk'].' = ?';
				$std=$db->prepare($sql);
				$bound=array($pk);
				$std->execute($bound);
				foreach ($citys as $cityId){
					$sql = 'replace into '.$config['table'].'_city_map (`'.$config['pk'].'`, `cityId`) values (?, ?)';
					$std=$db->prepare($sql);
					$bound=array($pk, $cityId);
					$std->execute($bound);
				}
				
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
	$row['citys']=array();
	$sql = 'select * from ' . $config ['table'] . '_city_map where ' . $config ['pk'] . ' = ?';
	$std=$db->prepare($sql);
	$bound=array($pk);
	$std->execute($bound);
	$rowCitys=array();
	while(($ciRow=$std->fetch())!==false){
		$row['citys'][]=$ciRow['cityId'];
	}
} else {
	$row = array ();
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
					<div class="jarviswidget" id="product-edit-basic" data-widget-deletebutton="false" data-widget-editbutton="false"
						data-widget-custombutton="false">
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i>
							</span>
							<h2>特約商店基本資料管理</h2>

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
											<section class="col col-12">
												<label class="label">類別</label>
												<div class="inline-group">
													<?php foreach (\Model\Merchant\Get::$categories as $cId=>$cRow){?>
													<label class="radio"> <input type="radio" name="cId" value="<?php echo $cId?>"
														<?php if($row['cId']==$cId) echo 'checked';?>> <i></i><?php echo $cRow['name']?></label>
													<?php }?>
												</div>
											</section>
											<section class="col col-6">
												<label class="label">標題</label> <label class="input"> <input type="text" name="name" class="input"
													value="<?php echo $row['name']?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">簡述</label> <label class="input"> <input type="text" name="desc" class="input"
													value="<?php echo $row['desc']?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">上線日期</label> <label class="input"> <input type="text" name="startDate" class="datepicker"
													value="<?php echo $row['startDate']?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">&nbsp;</label> <label class="input"> <input type="text" name="endDate" class="datepicker"
													value="<?php echo $row['endDate']?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">Slogan</label>
												<div class="inline-group">
													<label class="radio"> <input type="radio" name="slogan" value="0" <?php if($row['slogan']=='0') echo 'checked';?>> <i></i>無
													</label> <label class="radio"> <input type="radio" name="slogan" value="1"
														<?php if($row['slogan']=='1') echo 'checked';?>> <i></i>HOT
													</label> <label class="radio"> <input type="radio" name="slogan" value="2"
														<?php if($row['slogan']=='2') echo 'checked';?>> <i></i>NEW
													</label>
												</div>
											</section>
											<section class="col col-6">
												<label class="label">狀態</label>
												<div class="inline-group">
													<label class="radio"> <input type="radio" name="status" value="0" <?php if($row['status']=='0') echo 'checked';?>> <i></i>隱藏
													</label> <label class="radio"> <input type="radio" name="status" value="1"
														<?php if($row['status']=='1') echo 'checked';?>> <i></i>顯示
													</label> <label class="radio"> <input type="radio" name="status" value="2"
														<?php if($row['status']=='2') echo 'checked';?>> <i></i>顯示於首頁
													</label>
												</div>
											</section>
										</div>
										<div class="row">
											<section class="col col-xs-12">
												<label class="label">顯示縣市(未勾選則為全國)</label>
												<div class="inline-group">
													<?php foreach (Model\Merchant\Get::getCitys() as $cityId=>$cityName ){?>
													<label class="checkbox">
														<input type="checkbox" value="<?php echo $cityId?>" name="citys[]" <?php if(in_array($cityId, $row['citys']))echo 'checked';?>>
														<i></i><?php echo $cityName?></label>
													<?php }?>
												</div>
											</section>
										</div>
										<div class="row">
											<section class="col col-xs-12">
												<label class="label">簡述</label> <label class="textarea textarea-resizable"> <textarea rows="30" cols="" name="content"
														class="ckeditor"><?php echo $row['content']?></textarea>
												</label>
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
												<label class="label"><?php echo $row['addDate']?> </label>
											</section>
											<section class="col col-3">
												<label class="label"><?php echo $row['adder']?></label>
											</section>
											<section class="col col-3">
												<label class="label"><?php echo $row['editDate']?></label>
											</section>
											<section class="col col-3">
												<label class="label"><?php echo $row['editor']?></label>
											</section>
										</div>
									</fieldset>


									<footer>
										<button type="submit" class="btn btn-primary">送出</button>
										<button type="button" class="btn btn-default" onclick="location.href='<?php echo $config['backUrl']?>'">返回</button>
									</footer>
									<input type="hidden" name="action" value="isSubmit" /> <input type="hidden" name="<?php echo $config['pk']?>"
										value="<?php echo $pk;?>" />
								</form>

							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->
					
				</article>
				
				<article class="col-sm-12 col-md-12 col-lg-6">
				<div class="jarviswidget jarviswidget-color-blueDark" id="product-" data-widget-deletebutton="false"
						data-widget-editbutton="false" data-widget-custombutton="false">
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
										<label class="select"> <select class="select" id="add-relative-catagories-select"
												onchange="change_relative_catagories_select();">
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
									<button type="button" class="btn btn-sm btn-info" onclick="add_location(); ">新增</button>
								</div>
								<div class="widget-body-toolbar"></div>
								<table id="product-relative-table" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td>特店名稱</td>
											<td>地址</td>
											<td>Lantitude</td>
											<td>Longitude</td>
										</tr>
									</thead>
									<tbody>
										<?php foreach (Model\Merchant\Get::getLocations($pk) as $paRow):?>
										
										<tr>
											<td><?php echo $paRow['name']?></td>
											<td><?php echo $paRow['address']?></td>
											<td><?php echo $paRow['lantitude']?></td>
											<td><?php echo $paRow['longitude']?></td>
											<td>
												<button type="button" class="btn btn-info btn-sm"
													onclick="edit_location('<?php echo $pk;?>', '<?php echo $paRow['mlId']?>', '<?php echo $paRow['name']?>', '<?php echo $paRow['lantitude']?>', '<?php echo $paRow['longitude']?>', '<?php echo $paRow['address']?>')">編輯</button>
												<button type="button" class="btn btn-warning btn-sm"
													onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?action=deleteLocation&mlId=<?php echo $paRow['mlId']?>&mId=<?php echo $pk?>'">刪除</button>
											</td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
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

<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form class="form form-inline " role="form">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title" id="myModalLabel">特店地圖</h4>
				</div>
				<div class="modal-body">

					<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="特店地圖名稱" name="name" id="mlName" required="" />
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="lantitude" id="lantitude" name="lantitude" />
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="longitude" id="longitude" name="longitude" />
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="地址" id="address" name="address" />
							</div>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					<button type="submit" class="btn btn-primary">送出</button>
				</div>
			</div>
			<!-- /.modal-content -->
			<input type="hidden" name="action" value="editLocation" /> <input type="hidden" name="mId" value="<?php echo $pk?>" /> <input
				type="hidden" name="mlId" id="mlId" value="0" />
		</form>
	</div>
	<!-- /.modal-dialog -->
</div>

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
<script type="text/javascript">
//<![CDATA[

//]]>
</script>
<script>
function add_location(){
	$('#mlName').val('');
	$('#mlId').val('');
	$('#lantitude').val('');
	$('#longitude').val('');
	$('#address').val('');
	$('#myModal').modal('show');
}
function edit_location(mId, mlId, name, lantitude, longitude, address){
	$('#mlName').val(name);
	$('#mlId').val(mlId);
	$('#lantitude').val(lantitude);
	$('#longitude').val(longitude);
	$('#address').val(address);
	$('#myModal').modal('show');
	
}
CKEDITOR.on( 'instanceReady', function(){
  $("textarea.ckeditor").cke_resize();
})
	$(document).ready(function() {
// 		$('#product-accessory-table').dataTable({
// 			"sPaginationType" : "bootstrap_full",
// 			stateSave: true
// 		});
		jQuery.fn.cke_resize = function() {
			   return this.each(function() {
			      var $this = $(this);
			      var rows = $this.attr('rows');
			      var height = rows * 20;
			      $this.next("div.cke").find(".cke_contents").css("height", height);
			   });
			};
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