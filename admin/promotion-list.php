<?php
use Routine\Db;
require_once ( "inc/init.php" );
require_once ( "inc/config.ui.php" );
Routine\Permission::checkPermission ( 'SPM', true );
$page_title = "促銷管理列表";
$page_css [] = "your_style.css";
include ( "inc/header.php" );

// include left panel (navigation)
// follow the tree in inc/config.ui.php
$page_nav ["promotion"] ['sub'] ['promotion-list'] ['active'] = true;
include ( "inc/nav.php" );

$db = Db::getInstance ();

$page = ( int ) $_GET ['page'];
// Config
$config = array (
		'tblName' => 'av_promotion',
		'pk' => 'promotion_id',
		'listUrl' => $_SERVER ['PHP_SELF'],
		'title' => $page_title,
		'editUrl' => 'promotion-edit.php',
		'initWhere' => ' where 1 ',
		'initSort' => ' order by a.`start_date`  ',
		'initDesc' => ' desc',
		'cookieName' => 'av_promotion',
		'editPermission' => 'EPM',
		'sortable' => false,
		'sortField' => 'start_date',
		'sortCondition' => '',
		'defaultPageCount' => 20 
);
$Grid = new Routine\Grid ( $config );
$paginate = new Routine\Paginate ( $config );

if (isset ( $_COOKIE [$config ['cookieName'] . '_page_count'] )) {
	$page_count = $_COOKIE [$config ['cookieName'] . '_page_count'];
} else {
	$page_count = $config ['defaultPageCount'];
}

$action = ( string ) $_REQUEST ['action'];
switch ($action) {
	case 'delete' :
		$sql = 'delete from ' . $config ['tblName'] . ' where `' . $config ['pk'] . '` = :pk';
		$std = $db->prepare ( $sql );
		$bound = array (
				'pk' => $_REQUEST [$config ['pk']] 
		);
		if ($std->execute ( $bound )) {
			msg ( '刪除成功', null, $_SERVER ['PHP_SELF'] );
		} else {
			msg ( '刪除失敗', null, $_SERVER ['PHP_SELF'] );
		}
		exit ();
		break;
	case 'change_page_count' :
		setcookie ( $config ['cookieName'] . "_page_count", $_REQUEST ['page_count'], 0 );
		$page_count = $_REQUEST ['page_count'];
		break;
	case 'change_sort' :
		$sql = 'update  `' . $config ['tblName'] . '` set `' . $config ['sortField'] . '` = `' . $config ['sortField'] . '` + 1 where ' . $config ['sortField'] . ' >= ?' . '';
		if (! empty ( $config ['sortCondition'] )) {
			$sql .= ' ' . $config ['sortCondition'];
		}
		$std = $db->prepare ( $sql );
		$std->execute ( array (
				$_REQUEST ['sort_number'] 
		) );
		$sql = 'update  `' . $config ['tblName'] . '` set `' . $config ['sortField'] . '` = ?  where ' . $config ['pk'] . ' = ?' . '';
		
		$std = $db->prepare ( $sql );
		$std->execute ( array (
				$_REQUEST ['sort_number'],
				$_REQUEST [$config ['pk']] 
		) );
		$sql = 'set @x = 0';
		$db->query ( $sql );
		$sql = 'update ' . $config ['tblName'] . ' set `' . $config ['sortField'] . '` = (@x:=@x+1)';
		if (! empty ( $config ['sortCondition'] )) {
			$sql .= ' ' . $config ['sortCondition'];
		}
		$sql .= 'order by `' . $config ['sortField'] . '`';
		$db->query ( $sql );
		// exit;
		
		break;
}

$Grid->setSearch ( array (
		array (
				'type' => 'like',
				'name' => 'name',
				'alias' => 'a',
				'dbField' => 'name' 
		) 
) );
$Grid->execute ();

$count_sql = "select count(*) from " . $config ['tblName'] . " a   " . $Grid->whereSql . $Grid->groupSql;
$query_sql = "select a.*
			from " . $config ['tblName'] . " a 
		  " . $Grid->whereSql . $Grid->groupSql . $Grid->orderSql;
$pData = array ();
if (empty ( $page ))
	$page = 1;

$paginate->setCountSql ( $count_sql );
$paginate->setQuerySql ( $query_sql );
$paginate->setPage ( $page );
$paginate->setPageCount ( $page_count );
$paginate->addBound ( $Grid->bound );
$paginate->paginate ();
// print_r($Grid->bound);
if ($paginate->result ['total_num'] > 0) {
	$sql = $paginate->result ['query_sql'];
	$pre = $db->prepare ( $sql );
	$pre->execute ( $paginate->bound );
	$options = array (
			'1' => '啟用',
			'2' => '停用' 
	);
	while ( ( $row = $pre->fetch ( PDO::FETCH_ASSOC ) ) !== false ) {
		$pData [] = $row;
	}
} else
	$pData = array ();

if ($config ['sortable'] === true) {
	$sql = 'select MAX(`' . $config ['sortField'] . '`) + 1 from ' . $config ['tblName'] . ' where 1';
	if (! empty ( $config ['sortCondition'] )) {
		$sql .= ' ' . $config ['sortCondition'];
	}
	$std = $db->prepare ( $sql );
	
	$std->execute ();
	$maxSort = $std->fetchColumn ();
}
?>

<style>
.list-body td a {
	margin: 0 5px 0 5px;
}

.list-body thead th {
	text-align: center;
}
.promotion-type-picker{
	cursor:pointer;
}
</style>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
	// configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
	// $breadcrumbs["New Crumb"] => "http://url.com"
	// $breadcrumbs["Tables"] = "";
	include ( "inc/ribbon.php" );
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark">
					<i class="fa fa-table fa-fw "></i> <?php echo $page_title?>
				</h1>
			</div>
			<?php if(Routine\Permission::checkPermission($config['editPermission'], false)):?>
			<div style="float: right; margin: 10px 20px;">
				<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#pick-promotion">
					新增
				</button>
				<!-- <a class="btn btn-primary" href="<?php echo $config['editUrl']?>">
					<i class="fa fa-lg fa-fw fa-plus"></i>新增
				</a> -->
			</div>
			<?php endif;?>
		</div>

		<!-- widget grid -->
		<section id="widget-grid" class="">

			<!-- row -->
			<div class="row">

				<!-- NEW WIDGET START -->
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-blueDark" id="widget-account-list" data-widget-deletebutton>
						<header>
							<span class="widget-icon"> <i class="fa fa-table"></i>
							</span>
							<h2><?php echo $page_title?></h2>
						</header>

						<!-- widget div-->
						<div>

							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<form action="<?=$_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data" name="sform">
									名稱:
									<input name="search[name]" value="<?php echo $Grid->searchData['name'];?>" class="input" />
									<button class="btn btn-default" type="submit">搜尋</button>
									<a class="btn btn-default" href="<?php echo $_SERVER['PHP_SELF']?>?doing=reset" rel="tooltip" data-placement="top" data-original-title="清除搜尋條件">顯示全部</a>
									<input name="is_search" type="hidden" value="1" />
									<div class="btn-group">
										<a class="btn btn-default" href="javascript:void(0);">每頁數量</a>
										<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
											<span class="caret"></span>
										</a>
										<ul class="dropdown-menu">
											<li><a href="<?php echo $_SERVER['PHP_SELF']?>?action=change_page_count&page_count=10">10</a></li>
											<li><a href="<?php echo $_SERVER['PHP_SELF']?>?action=change_page_count&page_count=20">20</a></li>
											<li><a href="<?php echo $_SERVER['PHP_SELF']?>?action=change_page_count&page_count=50">50</a></li>
											<li><a href="<?php echo $_SERVER['PHP_SELF']?>?action=change_page_count&page_count=1">100</a></li>
										</ul>
									</div>
								</form>

							</div>
							<!-- end widget edit box -->

							<!-- widget content -->
							<div class="widget-body list-body">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th><a href="<?=$_SERVER['PHP_SELF']?>?sort=name" <?php if($key == 'catagories_id') echo 'style="color:blue;"'?>>活動名稱</a></th>
											<th><a href="<?=$_SERVER['PHP_SELF']?>?sort=type" <?php if($key == 'name') echo 'style="color:blue;"'?>>種類</a></th>
											<th><a href="<?=$_SERVER['PHP_SELF']?>?sort=brand_id" <?php if($key == 'brand_id') echo 'style="color:blue;"'?>>開始時間</a></th>
											<th><a href="<?=$_SERVER['PHP_SELF']?>?sort=price" <?php if($key == 'price') echo 'style="color:blue;"'?>>結束時間</a></th>
											<th><a href="<?=$_SERVER['PHP_SELF']?>?sort=discount" <?php if($key == 'discount') echo 'style="color:blue;"'?>>活動內容</a></th>
											<th style="width: auto;">操作</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($pData as $row){?>
										<tr>
											<td><?php echo $row['name']?></td>
											<td><?php echo Web\Promotion::$types[$row['type']]['title']?></td>
											<td><?php echo $row['start_date']?></td>
											<td><?php echo $row['end_date']?></td>
											<td><?php echo $row['description']?></td>
											<td>
												<?php if(Routine\Permission::checkPermission($config['editPermission'], false)):?>
												<a class="btn btn-primary" href="<?php echo $config['editUrl']?>?<?php echo $config['pk']?>=<?php echo $row[$config['pk']]?>">
													<i class="fa fa-lg fa-fw fa-edit"></i>修改
												</a> <a class="btn btn-danger" href="#"
													onclick="if(confirm('確定刪除?')){location.href='<?php echo $_SERVER['PHP_SELF']?>?action=delete&<?php echo $config['pk']?>=<?php echo $row[$config['pk']]?>'}">
													<i class="fa fa-lg fa-fw fa-trash-o"></i>刪除
												</a>
													<?php if($config['sortable']===true):?>
													<div class="btn-group">
													<a class="btn btn-default" href="javascript:void(0);">排序</a>
													<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
														<span class="caret"></span>
													</a>
													<ul class="dropdown-menu">
															<?php for($i=1;$i<=$maxSort;$i++):?>
															<li><a href="<?php echo $_SERVER['PHP_SELF']?>?action=change_sort&<?php echo $config['pk']?>=<?php echo $row[$config['pk']];?>&sort_number=<?php echo $i;?>"><?php echo $i;?></a></li>
															<?php endfor;?>
														</ul>
												</div>
													<?php endif;?>
												<?php endif;?>
												</td>
										</tr>
										<?php }?>
									</tbody>
								</table>
								<ul class="pagination">

									<li class="<?php if(!$paginate->result['previous_area']) echo ' disabled';?>"><a href="<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo $paginate->result['previous_area']?>">←← 上十頁</a></li>
									<li class="<?php if(!$paginate->result['previous_page']) echo ' disabled';?>"><a href="<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo $paginate->result['previous_page']?>">← 上一頁</a></li>
									<?php for($i = $paginate->result['start_page'];$i<=$paginate->result['end_page'];$i ++){?>
									<li class="<?php if($page == $i) echo 'active';?>"><a href="<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo $i;?>"><?php echo $i;?></a></li>
									<?php }?>
									<li class="<?php if(!$paginate->result['next_page']) echo ' disabled';?>"><a href="<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo $paginate->result['next_page']?>">下一頁 →</a></li>
									<li class="<?php if(!$paginate->result['next_area']) echo ' disabled';?>"><a href="<?php echo $_SERVER['PHP_SELF']?>?page=<?php echo $paginate->result['next_area']?>">下十頁 →→</a></li>
								</ul>
							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
					<!-- end widget -->

					<!-- Widget ID (each widget will need unique ID)-->
					<!-- end widget -->

				</article>
				<!-- WIDGET END -->

				<!-- NEW WIDGET START -->
				<!-- WIDGET END -->

			</div>

			<!-- end row -->

			<!-- row -->


			<!-- end row -->

		</section>
		<!-- end widget grid -->
		<div class="modal fade" id="pick-promotion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="myModalLabel">請選擇欲新增的行銷組合種類</h4>
					</div>
					<div class="modal-body">
						<?php foreach (Web\Promotion::$types as $type=>$type_row):?>
						<div class="row">
							<div class="col-xs-12">
								<div class="panel panel-<?php echo $type_row['color']?>">
									<div class="panel-heading">
										<h3 class="panel-title"><?php echo $type_row['title']?></h3>
									</div>
									<div class="panel-body promotion-type-picker" onclick="location.href='promotion-edit.php?type=<?php echo $type?>'">
										<h3><?php echo $type_row['desc']?></h3>
									</div>
								</div>
							</div>
				        </div>
				        <?php endforeach;?>
		
					</div>
					<!-- div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							Cancel
						</button>
						<button type="button" class="btn btn-primary">
							Post Article
						</button>
					</div-->
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div>
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

	$(document).ready(function() {
		<?php if(count($Grid->searchData)>0){?>
		$('.jarviswidget-editbox').show();
		<?php }?>
		// PAGE RELATED SCRIPTS
// 			pageSetUp();
	})

</script>

<?php
// include footer
include ( "inc/footer.php" );
?>