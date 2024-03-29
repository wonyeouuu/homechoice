<?php
use Routine\Db;
require_once ( "inc/init.php" );
require_once ( "inc/config.ui.php" );
Routine\Permission::checkPermission ( 'SUC', true );
$page_title = "使用者身分";
$page_css [] = "your_style.css";
include ( "inc/header.php" );

// include left panel (navigation)
// follow the tree in inc/config.ui.php
$page_nav ["capacity"] ['active'] = true;
include ( "inc/nav.php" );

$db = Db::getInstance ();

$page = ( int ) $_GET ['page'];
// Config
$config = array (
		'tblName' => 'member_login',
		'pk' => 'mlId',
		'listUrl' => $_SERVER ['PHP_SELF'],
		'title' => $page_title,
		'editUrl' => 'install-edit.php',
		'initWhere' => ' where 1 ',
		'initSort' => ' order by a.`addDate`  ',
		'initDesc' => ' desc',
		'cookieName' => 'member_login',
		'editPermission' => 'MSR',
		'sortable' => false,
		'sortField' => 'sort_number',
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
$query_sql = "select a.*, b.name sourceName
			from " . $config ['tblName'] . " a 
					left join source b on a.sId = b.sId
		  " . $Grid->whereSql . $Grid->groupSql . $Grid->orderSql;
$pData = array ();
echo $count_sql;
echo '<br>'.$query_sql;
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
		$row['systemName'] = \Model\System\Storage::$systems[$row['sId']]['name'];
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
				<a class="btn btn-primary" href="<?php echo $config['editUrl']?>">
					<i class="fa fa-lg fa-fw fa-plus"></i>新增
				</a>
			</div>
			<?php endif;?>
			<?php if(Routine\Permission::checkPermission('ESR', false)):?>
			<div style="float: right; margin: 10px 20px;">
				<a class="btn btn-primary" href="memberlogin-export.php">
					<i class="fa fa-lg fa-fw fa-plus"></i>匯出
				</a>
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
											<th><a href="<?=$_SERVER['PHP_SELF']?>?sort=addDate" <?php if($key == 'addDate') echo 'style="color:blue;"'?>>日期</a></th>
											<th><a href="<?=$_SERVER['PHP_SELF']?>?sort=system" <?php if($key == 'system') echo 'style="color:blue;"'?>>系統</a></th>
											<th><a href="<?=$_SERVER['PHP_SELF']?>?sort=capacity" <?php if($key == 'capacity') echo 'style="color:blue;"'?>>身分</a></th>
											<th style="width: auto;">操作</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($pData as $row){?>
										<tr>
											<td><?php echo $row['addDate']?></td>
											<td><?php echo $row['system']?></td>
											<td><?php echo $row['capacity']?></td>
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