<?php

namespace Web;

class Colortable {
	public static function getShow($row, $class = null, $options=array()) {
		$html = '';
		$html .= '';
		if($options['addAnchor']===true){
// 		$html .= '<a href="product-edit.php?action=add_product_color&product_id='.$row['product_id'].'&colortable_id='.$row['colortable_id'].'">';
		}
		if (! empty ( $row ['code'] )) {
			$html .=  '<div class="' . ( $class != null ? $class : '' ) . '" style="background-color: ' . $row ['code'] . ';width:40px;height:40px;"></div>';
		}
		if (is_file ( MediaAbs . '/' . \Routine\FileField::$colortableFiles [0] ['dir'] . '/' . $row ['image'] )) {
			
			$html .= '<img class="' . ( $class != null ? $class : '' ) . '" src="' . MediaUrl . '/' . \Routine\FileField::$colortableFiles [0] ['dir'] . '/' . $row ['image'] . '" data-product_id="'.$row['product_id'].'" data-colortable_id="'.$row['colortable_id'].'" />';
			
			
		}
		if($options['addAnchor']===true){
// 		$html .= '</a>';
		}
// 		$html .= '<input class="product-color-stock-input" data-product_id="'.$row['product_id'].'" data-colortable_id="'.$row['colortable_id'].'" name="stock['.$row['colortable_id'].']" />';
// 		$html .= '</div>';
		return $html;
	}
}