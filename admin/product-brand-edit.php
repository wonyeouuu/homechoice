<?php
use Routine\Db;
require_once ( "inc/init.php" );
require_once ( "inc/config.ui.php" );
Routine\Permission::checkPermission('EPB', true);
$page_title = "產品品牌管理";

$page_css [] = "your_style.css";
include ( "inc/header.php" );

$page_nav["product"]['sub']['product-brand-list']['active'] = true;
include ( "inc/nav.php" );
$config = array (
		'pk' => 'brand_id',
		'table' => 'av_product_brand',
		'backUrl' => 'product-brand-list.php' 
);

$pk = ( int ) $_REQUEST [$config ['pk']];
$db = Db::getInstance ();
$permissionSetting = \Routine\Permission::$permissions;
switch ($_REQUEST ['action']) {
	case 'isSubmit' :
		$fields = array (
				'name',
				'text'
		);
		if (empty ( $pk )) {
			
			$sql = 'insert into ' . $config ['table'];
			$sql .= '(' . $db->getFieldsSeparateByDot ( $fields ) . ') values';
			$sql .= '(' . $db->getColonFieldsSeparateByDot ( $fields ) . ')';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $fields, $_POST );
			$pk = $db->lastInsertId();
			Routine\FileField::uploadBackendFiles ( Routine\FileField::$brandFiles, $config['table'], $config['pk'], $pk );
				
			if ($std->execute ( $bound )) {
				msg ( '新增成功', null, $config ['backUrl'] );
			} else {
				msg ( '失敗', null, $config ['backUrl'] );
			}
		} else {
			$sql = 'update ' . $config ['table'] . ' set ' . $db->getUpdateColon ( $fields ) . ' where ' . $config ['pk'] . ' = :pk';
			$std = $db->prepare ( $sql );
			$bound = $db->getBound ( $fields, $_POST );
			$bound ['pk'] = $pk;
			Routine\FileField::uploadBackendFiles ( Routine\FileField::$brandFiles, $config['table'], $config['pk'], $pk );
			if ($std->execute ( $bound )) {
				msg ( '修改成功', null, $config ['backUrl'] );
			} else {
				msg ( '修改失敗', null, $config ['backUrl'] );
			}
		}
		exit ();
		break;
	case 'deleteFile':
		$fileName=base64_decode($_REQUEST['file']);
		$field=$_REQUEST['field'];
		$sql = 'update '.$config['table'].' set `'.$field.'` = "" where `'.$config['pk'].'` = :pk';
		$std=$db->prepare($sql);
		$bound=array('pk'=>$pk);
		if($std->execute($bound)){
			$filePath=product_brand_abs.'/'.$fileName;
			@unlink($filePath);
		}
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
							<h2>產品類別資料管理</h2>

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
												<label class="label">名稱</label> <label class="input"> <input type="text" name="name" class="input" value="<?php echo $row['name']?>">
												</label>
											</section>
											<section class="col col-6">
												<label class="label">說明</label> 
												<label class="textarea textarea-resizable">
												<textarea rows="8" class="custom-scroll" name="text"><?php echo $row['text']?></textarea>
												</label>
											</section>
										</div>
									</fieldset>
<?php 
$fileSetting = Routine\FileField::$brandFiles;
?>
									<header>類別首頁圖</header>
									<fieldset>
										<?php foreach ($fileSetting as $fileKey =>$fileRow):?>
										<div class="row">
											<section class="col col-6">
												<label class="label">上傳檔案 
													<?php if(isset($fileRow['width']) || isset($fileRow['height'])):?>
													寬<?php echo $fileRow['width']?> x 高<?php echo $fileRow['height']?>
													<?php endif;?>
												</label>
												<div class="input input-file">
													<span class="button"><input type="file" name="<?php echo $fileRow['field']?>" onchange="this.parentNode.nextSibling.value = this.value">瀏覽</span><input type="text" placeholder="檔案" readonly="">
												</div>
											</section>
											<section class="col col-6">
												<?php if(is_file(product_brand_abs.'/'.$row[$fileRow['field']])):?>
												<img style="width:100%;" src="<?php echo product_brand_rel.'/'.$row['image']?>" />
												<button type="button" class="btn btn-default btn-xs" onclick="location.href='<?php echo $_SERVER['PHP_SELF']?>?action=deleteFile&file=<?php echo base64_encode($row['image'])?>&<?php echo $config['pk']?>=<?php echo $pk;?>&field=<?php echo $fileRow['field']?>'">刪除</button>
												<?php endif;?>
											</section>
										</div>
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
function getComAdminRowHtml(row){
	var html="";
	html += "<tr>";
	html += "	<td>"+row.UserID+"<\/td>";
	html += "	<td style=\"width: 88px;\">";
	html += "		<button class=\"btn btn-default\" type=\"button\" onclick=\"deleteComAdmin('"+row.UserID+"');\">";
	html += "		<i class=\"fa fa-trash-o\"><\/i> 刪除<\/button>";
	html += "	<\/td>";
	html += "<\/tr>";

	return html;
}
function reloadComAdminList(){
	$.ajax({
		url: 'ajax/ajax.php',
		dataType: 'json',
		type: 'post',
		data: {
			action: 'getComAdminList',
			CommunityID: <?php echo $pk?>
		},
		success: function(response){
			if(response.success == 0){
				$.bigBox({
					title : "失敗",
					content : response.errorMsg ? response.errorMsg : "",
					color : "#C46A69",
					//timeout: 6000,
					icon : "fa fa-warning shake animated",
					timeout : 5000
				});
			}else{
				var html = '';
				for(var i in response.comAdminList){
					html += getComAdminRowHtml(response.comAdminList[i]);
				}
				$('#comAdminTable tbody').html(html);
			}
		}
	});
}
function deleteComAdmin(UserID){
	$.ajax({
		url: 'ajax/ajax.php',
		dataType: 'json',
		type: 'post',
		data: {
			action: 'deleteComAdmin',
			CommunityID: <?php echo $pk?>,
			UserID:UserID
		},
		success: function(response){
			if(response.success == 0){
				$.bigBox({
					title : "失敗",
					content : response.errorMsg ? response.errorMsg : "",
					color : "#C46A69",
					//timeout: 6000,
					icon : "fa fa-warning shake animated",
					timeout : 5000
				});
			}else{
				reloadComAdminList();
			}
		}
	});
}
	$(document).ready(function() {
		$('#addUserID').autocomplete({
	      source: "searchUserID.php",
	      minLength: 2
	    });
	    $('#addUserBtn').click(function(){
		    var UserID = $('#addUserID').val();
		    if(UserID && UserID != ''){
			    $.ajax({
				    url: 'ajax/ajax.php',
				    type: 'post',
				    dataType: 'json',
				    data: {
					    action: 'addUserToCommunityAdmin',
					    UserID: UserID,
					    CommunityID: <?php echo $pk?>
				    },
				    success: function(response){
					    if(response.success == 0){
					    	$.bigBox({
								title : "失敗",
								content : response.errorMsg ? response.errorMsg : "",
								color : "#C46A69",
								//timeout: 6000,
								icon : "fa fa-warning shake animated",
								timeout : 5000
							});
					    }else{
					    	reloadComAdminList();
					    }
				    }
				});
		    }
		});
		reloadComAdminList();
		// PAGE RELATED SCRIPTS
	})

</script>

<?php
// include footer
include ( "inc/footer.php" );
?>