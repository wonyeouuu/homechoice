<?php

namespace Model\Click;

class Code {
	public static $codes = array (
			'10000' => array (
					'name' => '操作介紹及練習',
					'sub' => array (
							'10100' => array (
									'name' => '處方練習設定',
									'sub' => array (
											'10101' => array (
													'name' => '最末袋－愛多尼耳' 
											),
											'10102' => array (
													'name' => '最末袋－葡萄糖藥水' 
											),
											'10103' => array (
													'name' => '無最末袋－白天放空' 
											),
											'10104' => array (
													'name' => '潮式治療' 
											),
											'' => array (
													'name' => '' 
											) 
									) 
							),
							'10200' => array (
									'name' => '治療前準備' 
							),
							'10300' => array (
									'name' => '治療中問題',
									'sub' => array (
											'10301' => array (
													'name' => '機器警訊修正方法' 
											),
											'10302' => array (
													'name' => '手控引流' 
											),
											'10303' => array (
													'name' => '略過的操作方法' 
											),
											'10304' => array (
													'name' => '提前結束治療' 
											) 
									) 
							),
							'10400' => array (
									'name' => '治療完成下機' 
							) 
					) 
			),
			'20000' => array (
					'name' => '機器外觀' 
			),
			'30000' => array (
					'name' => '聽聽' 
			),
			'40000' => array (
					'name' => '治療方式介紹',
					'sub' => array (
							'' => array (
									'name' => 'APD治療介紹' 
							),
							'' => array (
									'name' => '透悉治療方式:超級比一比' 
							),
							'' => array (
									'name' => 'APD的常見q＆a' 
							) 
					) 
			),
			'50000' => array (
					'name' => '語言',
					'sub' => array (
							'50100' => array (
									'name' => '國語' 
							) ,
							'50200' => array (
									'name' => '英語' 
							) ,
							'50300' => array (
									'name' => '台語' 
							) ,
					) 
			) 
	);
}