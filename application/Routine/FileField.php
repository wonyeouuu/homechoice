<?php

namespace Routine;

/**
 *
 * @author Jeff
 *         name 阿就name, 後台的名稱會隨著這邊改
 *         field 資料表欄位及constant定義檔名, ***勿改***
 *         type image|file
 *         image	會根據ratio, width, height設定進行縮圖, 且後台會顯示圖片預覽
 *         file	只會上傳
 *         ext		限定的副檔名
 *         ratio false "H" "W" "B"
 *         false	需同時指派width跟height, 會將圖片縮放至指定的width跟height
 *         H		需指派height, 會將圖片以等比例縮放至指定高度
 *         W		需指派width, 會將圖片以等比例縮放至指定寬度
 *         B		需指派width跟height, 會將圖片以等比例縮放至指定寬度或指定高度為止
 *        
 */
class FileField {
	// 品質認證
	public static $catagoriesFiles = array (
			array (
					'name' => '首頁圖片',
					'field' => 'image',
					'dir' => 'images/product_catagories',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif' 
					) 
			// 'ratio' => 'false',
			// 'height' => 458,
			// 'width' => 1400
						) 
	);
	public static $brandFiles = array (
			array (
					'name' => '品牌圖片',
					'field' => 'image',
					'dir' => 'images/product_brand',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif' 
					) 
			// 'ratio' => 'false',
			// 'height' => 458,
			// 'width' => 1400
						) 
	);
	public static $newsFiles = array (
			array (
					'name' => '新聞圖片',
					'field' => 'image',
					'dir' => 'images/news',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif'
					)
					// 'ratio' => 'false',
					// 'height' => 458,
					// 'width' => 1400
			)
	);
	public static $productFiles = array (
			array (
					'name' => '產品預設圖片',
					'field' => 'image',
					'dir' => 'images/product',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif' 
					),
					'ratio' => 'false',
					'height' => 210,
					'width' => 210,
					'sub_dir' => 'pk' 
			) 
	);
	public static $bannerFiles = array (
			array (
					'name' => '首頁banner',
					'field' => 'pic',
					'dir' => 'images/banner',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif' 
					),
					'ratio' => 'false',
					'height' => 379,
					'width' => 640 
			) 
	);
	public static $accessoryFiles = array (
			array (
					'name' => '加購產品圖片',
					'field' => 'image',
					'dir' => 'images/accessory',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif' 
					),
					'ratio' => 'false',
					'height' => 300,
					'width' => 300 
			) 
	);
	public static function delTree($dir) {
		$files = array_diff ( scandir ( $dir ), array (
				'.',
				'..' 
		) );
		foreach ( $files as $file ) {
			( is_dir ( "$dir/$file" ) ) ? self::delTree ( "$dir/$file" ) : unlink ( "$dir/$file" );
		}
		return rmdir ( $dir );
	}
	public static function getImageAbs($fileName, $type, $row = null) {
		if ($type == 'banner') {
			if (is_file ( MediaAbs . '/images/banner/' . $fileName )) {
				return MediaAbs . '/images/banner/' . $fileName;
			}
		} elseif ($type == 'catagories') {
			if (is_file ( MediaAbs . '/images/product_catagories/' . $fileName )) {
				return MediaAbs . '/images/product_catagories/' . $fileName;
			}
		} elseif ($type == 'brand') {
			if (is_file ( MediaAbs . '/images/product_brand/' . $fileName )) {
				return MediaAbs . '/images/product_brand/' . $fileName;
			}
		} elseif ($type == 'product') {
			$path = MediaAbs . '/images/product/' . $row ['product_id'] . '/' . $fileName;
			if (is_file ( $path )) {
				return MediaAbs . '/images/product/' . $row ['product_id'] . '/' . $fileName;
			}
		}
		return '';
	}
	public static function getImageRel($fileName, $type, $row = null) {
		if ($type == 'banner') {
			if (is_file ( MediaAbs . '/images/banner/' . $fileName )) {
				return MediaUrl . '/images/banner/' . $fileName;
			}
		} elseif ($type == 'catagories') {
			if (is_file ( MediaAbs . '/images/product_catagories/' . $fileName )) {
				return MediaUrl . '/images/product_catagories/' . $fileName;
			}
		} elseif ($type == 'brand') {
			if (is_file ( MediaAbs . '/images/product_brand/' . $fileName )) {
				return MediaUrl . '/images/product_brand/' . $fileName;
			}
		} elseif ($type == 'product') {
			$path = MediaAbs . '/images/product/' . $row ['product_id'] . '/' . $fileName;
			if (is_file ( $path )) {
				return MediaUrl . '/images/product/' . $row ['product_id'] . '/' . $fileName;
			}
		} elseif ($type == 'product_color') {
			$path = MediaAbs . '/images/product/' . $row ['product_id'] . '/' . $row ['colortable_id'] . '/' . $fileName;
			if (is_file ( $path )) {
				return MediaUrl . '/images/product/' . $row ['product_id'] . '/' . $row ['colortable_id'] . '/' . $fileName;
			}
		}elseif($type == 'colortable'){
			$path = MediaAbs.'/images/colortable/'.$fileName;
			if(is_file($path)){
				return MediaUrl.'/images/colortable/'.$fileName;
			}
		}elseif ($type == 'news') {
			if (is_file ( MediaAbs . '/images/news/' . $fileName )) {
				return MediaUrl . '/images/news/' . $fileName;
			}
		} 
		return '';
	}
	public static function isExist($fileName, $type, $row = null) {
		if ($type == 'accessory') {
			if (is_file ( MediaAbs . '/images/accessory/' . $fileName )) {
				return true;
			}
		} elseif ($type == 'catagories') {
			if (is_file ( MediaAbs . '/images/catagories/' . $fileName )) {
				return true;
			}
		} elseif ($type == 'product') {
			$path = MediaAbs . '/images/product/' . $row ['product_id'] . '/' . $fileName;
			if (is_file ( $path )) {
				return true;
			}
		}elseif ($type == 'news') {
			$path = MediaAbs . '/images/news/'  . $fileName;
			if (is_file ( $path )) {
				return true;
			}
		} 
		return false;
	}
	public static function getImgRel($type, $field, $fileName) {
		$t = $type . 'Files';
		$config = self::$$t;
		foreach ( $config as $key => $value ) {
			if ($value ['field'] == $field) {
				if (is_file ( MediaAbs . '/' . $value ['dir'] . '/' . $fileName )) {
					return MediaUrl . '/' . $value ['dir'] . '/' . $fileName;
				} else {
					return false;
				}
			}
		}
	}
	public static function uploadBackendFiles($fileSettings, $table, $pk, $pkValue) {
		$db = \Routine\Db::getInstance ();
		$sceneFiles = $fileSettings;
		foreach ( $sceneFiles as $key => $value ) {
			$field = $value ['field'];
			if ($_FILES [$field] ['size'] > 0) {
				$sourceName = $_FILES [$field] ['name'];
				$sourceInfo = pathinfo ( $sourceName );
				$sourceExt = strtolower ( $sourceInfo ['extension'] );
				
				if (isset ( $value ['ext'] )) {
					if (! in_array ( $sourceExt, $value ['ext'] )) {
						msg ( 'Please upload the ' . implode ( ',', $value ['ext'] ) . ' extension file.' );
						continue;
					}
				}
				
				$fileName = $field . '_' . $pkValue . '_' . substr ( time (), - 8 ) . '.' . $sourceExt;
				$fileDir = MediaAbs . '/' . $value ['dir'];
				if (! is_dir ( $fileDir )) {
					@mkdir ( $fileDir, 0777 );
				}
				
				if ($value ['sub_dir']) {
					switch ($value ['sub_dir']) {
						case 'pk' :
							$fileDir .= '/' . $pkValue;
							break;
					}
				}
				$filePath = $fileDir . '/' . $fileName;
				
				if (! $result = move_uploaded_file ( $_FILES [$field] ['tmp_name'], $filePath )) {
					echo 'move failed';
					exit ();
				}
				
				if ($value ['type'] == 'image' && ( isset ( $value ['width'] ) || isset ( $value ['height'] ) )) {
					$ir = new ImageResize ( $filePath );
					$result = $ir->resize ( $value ['width'], $value ['height'], $value ['ratio'] );
					$ir->save ( $filePath );
				}
				
				$sql = 'update ' . $table . ' set ' . $field . ' = :fileName where ' . $pk . ' = :pk';
				$pre = $db->prepare ( $sql );
				$bound = array (
						'fileName' => $fileName,
						'pk' => $pkValue 
				);
				if (! $pre->execute ( $bound )) {
					print_r ( $pre->errorInfo () );
					exit ();
				}
			} else {
			}
		}
	}
}

?>