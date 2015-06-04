<?php
class JSLoader {
	public static function productStock() {
		$proList = Product::getAllFullProducts ();
		$html = "<script>\r\n";
		$html .= "var proStockConfig = {};\r\n";
		foreach ( $proList as $key => $value ) {
			$html .= "proStockConfig[" . $value ['proId'] . "] = " . $value ['proStock'] . ";\r\n";
		}
		$html .= "</script>\r\n";
		
		echo $html;
	}
	public static function productUnitPrice() {
		$proList = Product::getAllFullProducts ();
		$html = "<script>\r\n";
		$html .= "var proUnitPriceConfig = {};\r\n";
		foreach ( $proList as $key => $value ) {
			$html .= "proUnitPriceConfig[" . $value ['proId'] . "] = " . $value ['proPrice'] . ";\r\n";
		}
		$html .= "</script>\r\n";
		
		echo $html;
	}
	public static function couponCap() {
		$couList = Orders::getCouponCap ();
		$html = "<script>\r\n";
		$html .= "var couponCapConfig = {};\r\n";
		foreach ( $couList as $key => $value ) {
			$html .= "couponCapConfig[" . $value ['couCap'] . "] = {\r\n";
			$html .= "amount: " . $value ['couAmount'] . ", \r\n";
			$html .= "proId:  " . (empty ( $value ['proId'] ) ? 0 : $value ['proId']) . ",\r\n";
			$html .= "proName: \"" . $value ['proName'] . "\",\r\n";
			$html .= "proId2:  " . (empty ( $value ['proId2'] ) ? 0 : $value ['proId2']) . ",\r\n";
			$html .= "proName2: \"" . $value ['proName2'] . "\",\r\n";
			$html .= "proId3:  " . (empty ( $value ['proId3'] ) ? 0 : $value ['proId3']) . ",\r\n";
			$html .= "proName3: \"" . $value ['proName3'] . "\",\r\n";
			$html .= "};\r\n";
		}
		$html .= "</script>\r\n";
		
		echo $html;
	}
	public static function freightConfig() {
		$freightInf = Orders::getFreight ();
		$html = "<script>\r\n";
		$html .= "var freightConfig = " . $freightInf ['freight'] . ";\r\n";
		$html .= "var freightCapConfig = " . $freightInf ['freightCap'] . ";\r\n";
		$html .= "</script>\r\n";
		
		echo $html;
	}
}