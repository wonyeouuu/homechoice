<?php
/**
 * @author Jeff
 * name 	阿就name, 後台的名稱會隨著這邊改
 * field 	資料表欄位及constant定義檔名, ***勿改***
 * type 	image|file
 * 		image	會根據ratio, width, height設定進行縮圖, 且後台會顯示圖片預覽
 * 		file	只會上傳
 * ext		限定的副檔名
 * ratio false "H" "W" "B"
 * 		false	需同時指派width跟height, 會將圖片縮放至指定的width跟height
 * 		H		需指派height, 會將圖片以等比例縮放至指定高度
 * 		W		需指派width, 會將圖片以等比例縮放至指定寬度
 * 		B		需指派width跟height, 會將圖片以等比例縮放至指定寬度或指定高度為止
 *
 */
class FileField {
	// 品質認證
	public static $masFiles = array (
			array (
					'name' => '圖片',
					'field' => 'masPic',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif' 
					),
					'ratio' => 'false',
					'height' => 100,
					'width' => 100 
			) 
	);
	public static $proFiles = array (
			array (
					'name' => '圖片',
					'field' => 'proImg',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif' 
					) 
			) 
	);
	public static $resFiles = array (
			array (
					'name' => '圖片',
					'field' => 'corFile1',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif',
							'jpeg' 
					) 
			),
			array (
					'name' => '圖片',
					'field' => 'corFile2',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif',
							'jpeg' 
					) 
			),
			array (
					'name' => '圖片',
					'field' => 'corFile3',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif',
							'jpeg' 
					) 
			),
			array (
					'name' => '圖片',
					'field' => 'corFile4',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif',
							'jpeg' 
					) 
			),
			array (
					'name' => '圖片',
					'field' => 'corFile5',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif',
							'jpeg' 
					) 
			) 
	);
	public static $corFiles = array (
			array (
					'name' => '圖片',
					'field' => 'corFile1',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif',
							'jpeg' 
					) 
			),
			array (
					'name' => '圖片',
					'field' => 'corFile2',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif',
							'jpeg' 
					) 
			),
			array (
					'name' => '圖片',
					'field' => 'corFile3',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif',
							'jpeg' 
					) 
			),
			array (
					'name' => '圖片',
					'field' => 'corFile4',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif',
							'jpeg' 
					) 
			),
			array (
					'name' => '圖片',
					'field' => 'corFile5',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif',
							'jpeg' 
					) 
			) 
	);
	public static $blogFiles = array (
			array (
					'name' => '圖片',
					'field' => 'blogImg',
					'type' => 'image',
					'ext' => array (
							'jpg',
							'png',
							'gif' 
					) 
			) 
	);
	public static function getImgRel($type, $field, $fileName) {
		$t = $type . 'Files';
		$config = self::$$t;
		foreach ( $config as $key => $value ) {
			if ($value ['field'] == $field) {
				if (is_file ( constant ( $field . 'Abs' ) . DIRECTORY_SEPARATOR . $fileName )) {
					return constant ( $field . 'Rel' ) . '/' . $fileName;
				} else {
					return false;
				}
			}
		}
	}
}

?>