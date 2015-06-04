<?php
use Routine\Db;
require_once '../connect.php';
// echo json_encode($_FILES);
switch ($_REQUEST ['action']) {
	case 'changecolortable_id':
		$db = Db::getInstance();
		$image_id = (int)$_POST['imgId'];
		$colortable_id = (int)$_POST['colortable_idId'];
		
		$sql = 'update av_product_image set colortable_id = ? where image_id = ?';
		$std=$db->prepare($sql);
		$bound=array($colortable_id, $image_id);
		if($std->execute($bound)){
			$result = array (
					'success' => '1',
					'errorMsg'
			);
			echo json_encode($result);
			exit;
		}else{
			$result = array (
					'success' => '0',
					'errorMsg'
			);
			echo json_encode($result);
			exit;
		}
		
		break;
	case 'uploadPhoto' :
		$db = Db::getInstance();
		// echo json_encode($_FILES);
		// echo json_encode($_REQUEST);
		$product_id = ( int ) $_POST ['product_id'];
		$colortable_id = ( int ) $_POST ['colortable_id'];
		
		$sql = 'select MAX(sort_number) + 1 from av_product_image where product_id = ? and colortable_id = ?';
		$std = $db->prepare ( $sql );
		$std->execute ( array (
				$product_id ,
				$colortable_id
		) );
		$sort = $std->fetchColumn ();
		if(empty($sort)){
			$sort = 1;
		}
		
		$fileNames = array ();
		
		$result = array (
				'success' => '1',
				'errorMsg' 
		);
		
		foreach ( $_FILES ['photo'] ['size'] as $key => $fileSize ) {
			$filename = $_FILES ['photo'] ['name'] [$key];
			if ($fileSize <= 0) {
				$result ['success'] = '0';
				$result ['errorMsg'] .= ' ' . $filename . '影像損毀 ';
				continue;
			}
			if ($fileSize > 2000000) {
				$result ['success'] = '0';
				$result ['errorMsg'] .= ' ' . $filename . '大於2MB ';
				continue;
			}
			
			$filetype = $_FILES ['photo'] ['type'] [$key];
			if (strpos ( $filetype, 'image' ) === false) {
				$result ['success'] = '0';
				$result ['errorMsg'] .= ' ' . $filename . '非影像檔案 ';
				continue;
			}
			
			$ext = strtolower ( pathinfo ( $_FILES ['photo'] ['name'] [$key], PATHINFO_EXTENSION ) );
			
			$sql = 'insert into av_product_image (`product_id`, `sort_number`, `colortable_id`) values (?, ?, ?)';
			$std = $db->prepare ( $sql );
			$std->execute ( array (
					$product_id,
					$sort ,
					$colortable_id
			) );
			$image_id = $db->lastInsertId ();
			
			$fileName = 'product_' . $product_id.'_'.$colortable_id . '_' . $image_id . '_' . substr ( time (), - 8 ) . '.' . $ext;
			$dir = MediaAbs.'/images/product/'.$product_id.'/'.$colortable_id;
			$url = MediaUrl.'/images/product/'.$product_id.'/'.$colortable_id;
			
			if (move_uploaded_file ( $_FILES ['photo'] ['tmp_name'] [$key], $dir.'/'.$fileName )) {
				
				$ir = new Routine\ImageResize($dir.'/'.$fileName);
				$ir->resize(343, null, 'W');
				$ir->save($dir.'/'.$fileName);
				
				$sql = 'update av_product_image set image = ? where image_id = ?';
				$std = $db->prepare ( $sql );
				if ($std->execute ( array (
						$fileName,
						$image_id 
				) )) {
					$fileNames [] = array('image_url'=>$url . '/' . $fileName, 'image_id'=>$image_id, 'sort_number'=>$sort);
				}
				
				$sort ++;
			}else{
				$result['success'] = '0';
				$result['errorMsg'] .= ' '.$filename.'上傳失敗 ';
			}
		}
		
		$result['files']= $fileNames ;
		echo json_encode ( $result );
		
		break;
	case 'deletePhoto' :
		$db = Db::getInstance();
		$image_id = ( int ) $_POST ['image_id'];
		$sql = 'select product_id, colortable_id, image from av_product_image where image_id = ?';
		$std = $db->prepare ( $sql );
		$result = array (
				'success' => '0' 
		);
		$std->execute ( array (
				$image_id 
		) );
		list($product_id, $colortable_id, $fileName) = $std->fetch (\PDO::FETCH_NUM);
		$filePath = MediaAbs.'/images/product/'.$product_id.'/'.$colortable_id.'/'.$fileName;
		@unlink ( $filePath );
		$sql = 'delete from av_product_image where image_id = ?';
		$std = $db->prepare ( $sql );
		$result = array (
				'success' => '0' 
		);
		
		if ($std->execute ( array (
				$image_id 
		) )) {
			$result ['success'] = '1';
		} else {
			$result ['success'] = '0';
		}
		echo json_encode ( $result );
		break;
	case 'getPhotoList' :
		$db =Db::getInstance();
		$product_id = $_REQUEST['product_id'];
		$colortable_id = $_REQUEST['colortable_id'];
		$sql = 'select image, sort_number, image_id 
				from av_product_image a 
				where a.product_id = ? '.($colortable_id != null ?'and a.colortable_id = ?':'').' order by a.sort_number asc';
		$std = $db->prepare ( $sql );
		$bound=array($product_id);
		if($colortable_id != null){
			$bound[]=$colortable_id;
		}
		$std->execute ($bound);
		$list=array();
		while(($row=$std->fetch())!==false){
			if(is_file(MediaAbs.'/images/product/'.$product_id.'/'.$colortable_id.'/' .$row['image'])){
				$row['image_url'] = MediaUrl.'/images/product'.'/'.$product_id.'/'.$colortable_id.'/'.$row['image'];
			}else{
				$row['image_url'] = MediaUrl.'/'.$product_id.'/'.$colortable_id.'/'.$row['image'];
			}
			
			
			$list[]=$row;
		}
		$result = array (
				'success' => '1',
				'pList' => $list 
		);
		echo json_encode ( $result );
		break;
	case 'updateSort' :
		$db = Db::getInstance();
		
		$tarId = ( int ) $_REQUEST ['tarId'];
		$prevId = ( int ) $_REQUEST ['prevId'];
		$product_id = ( int ) $_REQUEST ['product_id'];
		
		$sql = 'select sort_number from av_product_image where image_id = ?';
		$std = $db->prepare ( $sql );
		$std->execute ( array (
				$tarId 
		) );
		$tarSort = $std->fetchColumn ();
		
		$sql = 'select sort_number from av_product_image where image_id = ?';
		$std = $db->prepare ( $sql );
		$std->execute ( array (
				$prevId 
		) );
		$prevSort = $std->fetchColumn ();
		
		$sql = 'update av_product_image set sort_number = sort_number + 1 where product_id = ? and sort_number > ?';
		$std = $db->prepare ( $sql );
		$std->execute ( array (
				$product_id,
				$prevSort 
		) );
		
		$sql = 'update av_product_image set sort_number = ? where image_id = ?';
		$std = $db->prepare ( $sql );
		$std->execute ( array (
				$prevSort + 1,
				$tarId 
		) );
		
		break;
}