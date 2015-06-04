<?php

namespace Web;

use Routine\Db;

class Promotion {
	public static $promotionList = array ();
	public static $promotionProductList=array();
	public static $nowPromotions = null;
	public static $detectCount = 0;
	public static $totalPromotionDiscountPrice = 0;
	public static $cartIdList = array ();
	public static $productQtyList = array ();
	public static $productPriceList = array ();
	public static $types = array (
			'1' => array (
					'title' => '組合式A',
					'desc' => '任選___件，可抵扣___元或___折',
					'color' => 'orange' 
			),
			'2' => array (
					'title' => '組合式B',
					'desc' => '商品A + 商品B可抵扣___元或___折',
					'color' => 'red' 
			),
			'3' => array (
					'title' => '商品折扣A',
					'desc' => '商品A 特惠價___元',
					'color' => 'purple' 
			),
			'4' => array (
					'title' => '商品折扣B',
					'desc' => '商品A 優惠___折',
					'color' => 'blue' 
			) 
	);
	public static function calculatePromotionFromCart() {
		if (empty ( \Web\Orders::$cartList )) {
			return array();
			throw new \Exception ( 'no cart' );
			
			return false;
		}
		self::$productQtyList = \Web\Orders::collateProductIdAndQtyFromCart ();
		$nowPromotions = self::getNowPromotion ();
		self::$cartIdList = \Web\Orders::$cartIdList;
		$cartList = \Web\Orders::$cartList;
		$productPriceList = \Web\Orders::collateProductIdAndPriceFromCart ();
		arsort ( $productPriceList, SORT_NUMERIC );
		self::$productPriceList = $productPriceList;
// 		print_r ( $nowPromotions );
		self::$detectCount = 0;
		self::detectPromotion ();
		foreach (self::$promotionList as $key => $row){
			self::$totalPromotionDiscountPrice +=  $row['discountPrice'];
		}
		return self::$promotionList;
	}
	public static function detectPromotion() {
		self::$detectCount ++;
		$promotions = self::getNowPromotion ();
// 		echo '<br>開始促銷結果: ';
// 		print_r ( self::$promotionList );
// 		echo '<br >';
// 		echo '開始參數: 次數 ' . self::$detectCount . ', ';
// 		print_r ( array (
// 				self::$productQtyList,
// 				self::$productPriceList,
// 				self::$cartIdList 
// 		) );
		
// 		echo '<br />';
		
		foreach ( $promotions as $promotionRow ) {
			$type = $promotionRow['type'];
			
// 			echo 'now type = '.$type;
			
			switch ($promotionRow ['type']) {
				case '1' :
					$products = $promotionRow ['products'];
					$matchNeed = $promotionRow ['product_qty'];
					$discountAmount =  (1 - ('0.' . $promotionRow ['discount_amount']));
// 					$discountAmount =  $promotionRow ['discount_amount'];
					$result = array_intersect ( self::$cartIdList, $products );
// 					echo '<br />';
// 					echo 'type1比對結果: cartIdList ';
// 					print_r ( self::$cartIdList );
// 					echo 'products ';
// 					print_r ( $products );
// 					echo 'result ';
// 					print_r ( $result );
					
// 					echo '<br >';
					if (count ( $result ) == $matchNeed) {
						$matchIds = array ();
						$tmpQtyList = array ();
						foreach ( $result as $priceId ) {
							$matchIds [] = $priceId;
							$tmpQtyList [$priceId] = self::$productQtyList [$priceId];
						}
						
						// echo 'type 1 result equal : ';
						// print_r ( $result );
// 						echo 'match ids=';
// 						print_r ( $matchIds );
						$minQty = min ( $tmpQtyList );
					} elseif (count ( $result ) > $matchNeed) {
// 						echo '大於';
						$i = 1;
						$matchIds = array ();
						$tmpQtyList = array ();
						foreach ( self::$productPriceList as $priceId => $priceRow ) {
							$matchIds [] = $priceId;
							$tmpQtyList [$priceId] = self::$productQtyList [$priceId];
							if ($i >= $matchNeed) {
								break;
							}
							$i ++;
						}
						$minQty = min ( $tmpQtyList );
// 						echo 'matchIds=';
// 						print_r ( $matchIds );
// 						echo 'tmpQtyList=';
// 						print_r ( $tmpQtyList );
					} else {
						break;
					}
					
					$discountPrice = 0;
					foreach ( $tmpQtyList as $product_id => $product_qty ) {
						if (in_array ( $product_id, $matchIds )) {
							self::$productQtyList [$product_id] -= $minQty;
						}
						if (self::$productQtyList [$product_id] < 0) {
// 							echo '<br />錯誤促銷內容=';
// 							print_r ( $promotionRow );
// 							echo '<br />';
// 							echo '<br />minQty=' . $minQty . '<br />';
// 							print_r ( $matchIds );
// 							print_r ( self::$productQtyList );
// 							echo $product_id;
							throw new \Exception ( '數量計算錯誤' );
						}
						
						$discountUnitPrice += round(self::$productPriceList [$product_id] * $discountAmount);
// 						echo 'unit price = '.self::$productPriceList [$product_id].' x '.$discountAmount;
// 						echo ' , qty = '.$minQty;
// 						echo '<br />';
						if (self::$productQtyList [$product_id] == 0) {
							unset ( self::$productQtyList [$product_id] );
							$idKey = array_search ( $product_id, self::$cartIdList );
							unset ( self::$cartIdList [$idKey] );
							unset ( self::$productPriceList [$product_id] );
						}
					}
					$discountPrice = $discountUnitPrice * $minQty;
					self::$promotionList [] = array (
							'promotionInfor' => $promotionRow,
							'qty' => $minQty,
							'discountUnitPrice' => $discountUnitPrice,
							'discountPrice' => $discountPrice,
							'products' => $matchIds 
					);
// 					echo '<br> 結束促銷結果: ';
					
// 					print_r ( self::$promotionList );
// 					echo '<br >';
					
// 					echo '<br >結束參數:';
// 					print_r ( array (
// 							self::$productQtyList,
// 							self::$productPriceList,
// 							self::$cartIdList 
// 					) );
// 					echo '<br >';
					self::detectPromotion ( self::$productQtyList, self::$productPriceList, self::$cartIdList );
					break;
				case '2' :
// 					echo 'type2: ';
					$products = $promotionRow ['products'];
					$discountAmount = '0.' . $promotionRow ['discount_amount'];
					$discountAmount =  (1 - ('0.' . $promotionRow ['discount_amount']));
// 					echo 'type products=';
// 					print_r ( $products );
// 					echo 'cartidlist=';
// 					print_r ( self::$cartIdList );
// 					echo '<br />';
					if (isset ( self::$productQtyList [$products [0]] ) && isset ( self::$productQtyList [$products [1]] )) {
						$matchIds = $products;
						$tmpQtyList = array (
								$products [0] =>self::$productQtyList [$products [0]],
								$products [1] => self::$productQtyList [$products [1]] 
						);
						$minQty = min ( $tmpQtyList );
						foreach ( self::$productQtyList as $product_id => $product_qty ) {
							if (in_array ( $product_id, $matchIds )) {
								self::$productQtyList [$product_id] -= $minQty;
							}
							if (self::$productQtyList [$product_id] < 0) {
								echo '<pre>';
								echo '<br />錯誤促銷內容=';
								print_r ( $promotionRow );
								echo '<br />';
								echo '<br />minQty=' . $minQty . '<br />';
								print_r ( $matchIds );
								print_r ( $productQtyList );
								echo $product_id;
								echo '</pre>';
								throw new \Exception ( '數量計算錯誤' );
							}
							
							$discountUnitPrice += round(self::$productPriceList [$product_id] * $discountAmount);
							
							if (self::$productQtyList [$product_id] == 0) {
								unset ( self::$productQtyList [$product_id] );
								$idKey = array_search ( $product_id, self::$cartIdList );
								unset ( self::$cartIdList [$idKey] );
								unset ( self::$productPriceList [$product_id] );
							}
						}
						$discountPrice = $discountUnitPrice * $minQty;
						self::$promotionList [] = array (
								'promotionInfor' => $promotionRow,
								'qty' => $minQty,
								'discountUnitPrice' => $discountUnitPrice,
								'discountPrice' => $discountPrice,
								'products' => $matchIds 
						);
// 						echo '<br> 結束促銷結果: ';
						
// 						print_r ( self::$promotionList );
// 						echo '<br >結束參數: ';
						
// 						print_r ( array (
// 								self::$productQtyList,
// 								self::$productPriceList,
// 								self::$cartIdList 
// 						) );
// 						echo '<br >';
						self::detectPromotion ();
					}
					
					break;
				case '3' :
// 					echo 'haha';
					if (in_array ( $promotionRow ['product_id'], self::$cartIdList )) {
// 						echo 'hehehe';
						$discountAmount = '0.' . $promotionRow ['discount_amount'];
						$discountAmount =  $promotionRow ['discount_amount'];
						$product_id = $promotionRow ['product_id'];
						$minQty = self::$productQtyList [$product_id];
						$discountUnitPrice =  $discountAmount;
						$discountPrice = $discountUnitPrice * $minQty;
						$matchIds = array (
								$product_id 
						);
						unset ( self::$productQtyList [$product_id] );
						$idKey = array_search ( $product_id, self::$cartIdList );
						unset ( self::$cartIdList [$idKey] );
						unset ( self::$productPriceList [$product_id] );
						self::$promotionList [] = array (
								'promotionInfor' => $promotionRow,
								'qty' => $minQty,
								'discountUnitPrice' => $discountUnitPrice,
								'discountPrice' => $discountPrice,
								'products' => $matchIds 
						);
						self::detectPromotion ();
					}
					break;
				case '4' :
					if (in_array ( $promotionRow ['product_id'], self::$cartIdList )) {
						$discountAmount =  (1 - ('0.' . $promotionRow ['discount_amount']));
						$product_id = $promotionRow ['product_id'];
						$minQty = self::$productQtyList [$product_id];
						$discountUnitPrice =  $discountAmount * self::$productPriceList [$product_id];
						$discountPrice = $discountUnitPrice * $minQty;
						$matchIds = array (
								$product_id
						);
						unset ( self::$productQtyList [$product_id] );
						$idKey = array_search ( $product_id, self::$cartIdList );
						unset ( self::$cartIdList [$idKey] );
						unset ( self::$productPriceList [$product_id] );
						self::$promotionList [] = array (
								'promotionInfor' => $promotionRow,
								'qty' => $minQty,
								'discountUnitPrice' => $discountUnitPrice,
								'discountPrice' => $discountPrice,
								'products' => $matchIds
						);
						self::detectPromotion ();
					}
					break;
			}
		}
		return false;
	}
	public static function getNowPromotion() {
		if (self::$nowPromotions !== null) {
			return self::$nowPromotions;
		}
		$db = Db::getInstance ();
		$sql = 'select a.*, group_concat(b.product_id) product_id_group, group_concat(IFNULL(p.name, "")) product_name_group, p2.name product_name
				from av_promotion a
				left join av_promotion_product b on a.promotion_id = b.promotion_id
				left join av_product p on b.product_id = p.product_id
				left join av_product p2 on a.product_id = p2.product_id
				where a.start_date <= "' . date ( 'Y-m-d', time () ) . '" and a.end_date >= "' . date ( 'Y-m-d', time () ) . '"
				group by a.promotion_id
				order by FIELD(a.type, 2, 1, 4, 3)';
		$std = $db->prepare ( $sql );
		$list = array ();
		if ($std->execute ()) {
			while ( ( $row = $std->fetch () ) !== false ) {
				if (! empty ( $row ['product_id_group'] )) {
					$row ['products'] = explode ( ',', $row ['product_id_group'] );
				}
				if (! empty ( $row ['product_name_group'] )) {
					$row ['products_name'] = explode ( ',', $row ['product_name_group'] );
				}
				
				if(count($row['products'])>0){
					if(!$t = array_combine($row['products'], $row['products_name'])){
// 						echo '<pre>';
// 						print_r($row);
// 						print_r($row['products']);
// 						print_r($row['products_name']);
						exit;
					}
					self::$promotionProductList = ( $t+ self::$promotionProductList);
				}
				
				if ($row ['type'] == '2') {
					if (count ( $row ['products'] ) < 2) {
						continue;
					}
				}
				if ($row ['type'] == '3' || $row ['type'] == '4') {
					if (empty ( $row ['product_id'] )) {
						continue;
					}
				}
				
				$list [] = $row;
			}
		}
		self::$nowPromotions = $list;
		return $list;
	}
	public static function getPromotionProductList($promotion_id) {
		$db = Db::getInstance ();
		$sql = 'select b.*, c.name catagories_name
				from av_promotion_product a
				left join av_product b on a.product_id = b.product_id
				left join av_product_catagories c on b.catagories_id = c.catagories_id
				where a.promotion_id = ?';
		$std = $db->prepare ( $sql );
		$std->bindValue ( 1, $promotion_id );
		$list = array ();
		if ($std->execute ()) {
			while ( ( $row = $std->fetch () ) !== false ) {
				$list [] = $row;
			}
		}
		return $list;
	}
}