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
class Debug {
	public static function debug($o){
		echo '<div class="debug">';
		echo '<pre>';
		print_r($o);
		echo '</pre>';
		echo '</div>';
	}
}

?>