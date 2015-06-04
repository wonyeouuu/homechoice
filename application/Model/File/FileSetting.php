<?php

namespace Model\File;

class FileSetting {
	public static $productMedia = array (
			'copies' => array (
					'small' => array (
							'width' => 200,
							'ratio' => 'W',
							'type' => 'image',
							'dir' => 'images/product/$id/small',
							'iddir' => true 
					),
					'normal' => array (
							'width' => 1000,
							'ratio' => 'W',
							'type' => 'image',
							'dir' => 'images/product/$id/normal',
							'iddir' => true 
					) 
			) 
	);
	public static $couponMedia = array (
			'copies' => array (
					'small' => array (
							'width' => 200,
							'ratio' => 'W',
							'type' => 'image',
							'dir' => 'images/coupon/$id/small',
							'iddir' => true 
					),
					'normal' => array (
							'width' => 1000,
							'ratio' => 'W',
							'type' => 'image',
							'dir' => 'images/coupon/$id/normal',
							'iddir' => true 
					) 
			) 
	);
	public static $message = array (
			'dir' => 'message',
			'copies' => array (
					'small' => array (
							'width' => 200,
							'ratio' => 'W',
							'type' => 'image',
							'dir' => 'images/message/small' 
					),
					'normal' => array (
							'width' => 1000,
							'ratio' => 'W',
							'type' => 'image',
							'dir' => 'images/message/normal' 
					) 
			) 
	);
	public static $messagePdf = array (
			'dir' => 'files/messagepdf' 
	);
	public static $response = array (
			'dir' => 'files/message',
			'type' => 'file' 
	);
	public static $community = array (
			'dir' => 'images/community',
			'type' => 'image',
			'ratio' => false,
			'width' => 1000,
			'height' => 200 
	);
	public static $album = array (
			'dir' => 'album',
			'copies' => array (
					'small' => array (
							'width' => 200,
							'ratio' => 'W',
							'type' => 'image',
							'dir' => 'images/album/small' 
					),
					'normal' => array (
							'width' => 1000,
							'ratio' => 'W',
							'type' => 'image',
							'dir' => 'images/album/normal' 
					) 
			) ,
// 			'ratio' => true,
// 			'small' => array (
// 					'width' => 200,
// 					'ratio' => 'W',
// 					'type' => 'image',
// 					'dir' => 'images/album/small' 
// 			),
// 			'normal' => array (
// 					'width' => 1000,
// 					'ratio' => 'W',
// 					'type' => 'image',
// 					'dir' => 'images/album/normal' 
// 			) 
	);
}