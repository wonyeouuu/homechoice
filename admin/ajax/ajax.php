<?php
use Routine\Db;
$action = $_REQUEST ['action'];
if (empty ( $action )) {
	exit ();
}
require_once dirname ( dirname ( __FILE__ ) ) . '/connect.php';

if (function_exists ( $action )) {
	$action ();
	exit ();
}
exit ();
function delete_product_pic(){
	$product_id = ( int ) $_POST ['product_id'];
	$image = $_POST['image'];
	$Filefield=new Routine\FileField();
	$result = array (
			'success' => 0
	);
	
	$db = Routine\Db::getInstance();
	$sql = 'update av_product set image = "" where product_id = ?';
	$std=$db->prepare($sql);
	$bound=array($product_id);
	if($std->execute($bound)){
		$path = $Filefield->getImageAbs($image, 'product', array('product_id'=>$product_id));
		if(is_file($path)){
			@unlink($path);
		}
		$result['success'] = 1;
	}
	echo json_encode ( $result );
}
function change_relative_catagories_select(){
	$catagories_id = $_REQUEST['catagories_id'];
	$result = array (
			'success' => 0
	);
	$db = Db::getInstance();
	$sql = 'select product_id, name from av_product where 1';
	$bound=array();
	if($catagories_id !== ""){
		$sql .= ' and (catagories_id = :catagories_id or catagories_id2 = :catagories_id or catagories_id3 = :catagories_id)';
		$bound['catagories_id']=$catagories_id;
	}
	$std=$db->prepare($sql);
	if($std->execute($bound)){
		$list=array();
		while(($row=$std->fetch())!==false){
			$list[]=$row;
		}
		$result['list']=$list;
		$result['success'] = 1;
	}
	echo json_encode ( $result );
	
}
function down_product_relative(){
	$product_id = ( int ) $_POST ['product_id'];
	$relative_id = ( int ) $_POST ['relative_id'];
	$result = array (
			'success' => 0
	);
	if(Routine\Db::downSort($relative_id, $product_id, 'av_product_relative', 'relative_id', 'product_id', 'sort_number')){
		$result = array (
				'success' => '1'
		);
		
	}
	echo json_encode ( $result );
}
function up_product_relative(){
	$product_id = ( int ) $_POST ['product_id'];
	$relative_id = ( int ) $_POST ['relative_id'];
	$result = array (
			'success' => 0
	);
	if(Routine\Db::upSort($relative_id, $product_id, 'av_product_relative', 'relative_id', 'product_id', 'sort_number')){
		$result = array (
				'success' => '1'
		);
		
	}
	echo json_encode ( $result );
}
function down_product_accessory(){
	$product_id = ( int ) $_POST ['product_id'];
	$accessory_id = ( int ) $_POST ['accessory_id'];
	$result = array (
			'success' => 0
	);
	if(Routine\Db::downSort($accessory_id, $product_id, 'av_product_accessory', 'accessory_id', 'product_id', 'sort_number')){
		$result = array (
				'success' => '1'
		);
		
	}
	echo json_encode ( $result );
}
function up_product_accessory(){
	$product_id = ( int ) $_POST ['product_id'];
	$accessory_id = ( int ) $_POST ['accessory_id'];
	$result = array (
			'success' => 0
	);
	if(Routine\Db::upSort($accessory_id, $product_id, 'av_product_accessory', 'accessory_id', 'product_id', 'sort_number')){
		$result = array (
				'success' => '1'
		);
		
	}
	echo json_encode ( $result );
}
function add_product_relative(){
	$product_id = $_REQUEST['product_id'];
	$relative_id = $_REQUEST['relative_id'];
	$result=array('success'=>0);
	if(empty($product_id) || empty($relative_id)){
		echo json_encode($result);
	}
	$db = Db::getInstance();
	$sort_number = \Web\Product::getProductRelativeMaxSort($product_id);
	$sql = 'insert ignore into av_product_relative (`product_id`, `relative_id`, `sort_number`)
				values (?, ?, ?)';
	$std=$db->prepare($sql);
	$bound=array($product_id, $relative_id, $sort_number+1);
	
	if($std->execute($bound)){
		
		$inf=Web\Product::getProductInfor($relative_id);
		$result['success'] = 1;
		$result['infor']=$inf;
		
	}else{
	}
	echo json_encode($result);
	exit;
}
function add_product_accessory(){
	$product_id = $_REQUEST['product_id'];
	$accessory_id = $_REQUEST['accessory_id'];
	$result=array('success'=>0);
	if(empty($product_id) || empty($accessory_id)){
		echo json_encode($result);
	}
	$db = Db::getInstance();
	$sort_number = \Web\Product::getProductAccessoryMaxSort($product_id);
	$sql = 'insert ignore into av_product_accessory (`product_id`, `accessory_id`, `sort_number`)
				values (?, ?, ?)';
	$std=$db->prepare($sql);
	$bound=array($product_id, $accessory_id, $sort_number+1);
	
	if($std->execute($bound)){
		
		$inf=Web\Product::getAccessoryInfor($accessory_id);
		$result['success'] = 1;
		$result['infor']=$inf;
		
	}else{
	}
	echo json_encode($result);
	exit;
}
function delete_product_relative(){
	$product_id = (int)$_POST['product_id'];
	$relative_id = (int)$_POST['relative_id'];
	$result=array('success'=>0);
	$db = Db::getInstance();
	$sql = 'delete from av_product_relative where product_id = ? and relative_id = ?';
	$std=$db->prepare($sql);
	$bound=array($product_id, $relative_id);
	if($std->execute($bound)){
		$result['success'] = 1;
		
	}else{
	}
	echo json_encode($result);
	exit;
}
function delete_product_accessory(){
	$product_id = (int)$_POST['product_id'];
	$accessory_id = (int)$_POST['accessory_id'];
	$result=array('success'=>0);
	$db = Db::getInstance();
	$sql = 'delete from av_product_accessory where product_id = ? and accessory_id = ?';
	$std=$db->prepare($sql);
	$bound=array($product_id, $accessory_id);
	if($std->execute($bound)){
		$result['success'] = 1;
		
	}else{
	}
	echo json_encode($result);
	exit;
}