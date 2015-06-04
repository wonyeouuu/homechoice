<?php

namespace Model\File;

require_once dirname ( dirname ( dirname ( ( __FILE__ ) ) ) ) . '/aws-autoloader.php';
require_once dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) . '/class/ImageResize.php';
// use Aws\S3\S3Client;
use \Aws\S3\S3Client;
use \Aws\Common\Enum\Region;
use Model\Log\Log;

class Upload {
	private static $S3Key = 'AKIAJQGNNEWBOWVGRNNQ';
	private static $S3Secret = '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';
	private static $S3BucketName = 'file.umesh.tw';
	private static $tmpDir = null;
	public static $s3;
	public static function deleteSingleFile($dest) {
		self::_registerClient ();
		
		$result = self::$s3->deleteObject ( array (
				'Bucket' => self::$S3BucketName,
				'Key' => $dest 
		) );
	}
	public static function deleteMultiFiles($files = array()) {
		self::_registerClient ();
		$objects = array ();
		foreach ( $files as $file ) {
			$objects [] = array (
					'Key' => $file 
			);
		}
		
		$result = self::$s3->deleteObjects ( array (
				'Bucket' => self::$S3BucketName,
				'Objects' => $objects 
		) );
	}
	public static function uploadSingleFile($source, $dest, $options = array()) {
		$options += array (
				'ACL' => 'public-read',
				'FileSetting' => null 
		);
		self::_registerClient ();
		// Log::log('upload single start');;
		$results = array ();
		if ($options ['FileSetting'] !== null) {
			$FileSetting = $options ['FileSetting'];
			// Log::log('get file setting');
			// Log::log($FileSetting);
			if (isset ( $FileSetting ['copies'] ) && is_array ( $FileSetting ['copies'] )) {
				foreach ( $FileSetting ['copies'] as $copyType => $copySetting ) {
					
					if ($copySetting ['type'] == 'image' && isset ( $copySetting ['ratio'] ) && in_array ( $copySetting ['ratio'], array (
							true,
							'W',
							'H',
							'B' 
					) )) {
						$ir = new \ImageResize ( $source );
						$result = $ir->resize ( $copySetting ['width'], $copySetting ['height'], $copySetting ['ratio'] );
						$ir->save ( self::_getTmpDir () . '/' . $dest );
						$putFrom = self::_getTmpDir () . '/' . $dest;
					} else {
						$putFrom = $source;
					}
					
					if($copySetting['iddir'] === true && isset($options['id'])){
						$putTo = str_replace('$id', $options['id'],  $copySetting ['dir']) . '/' . $dest;
					}else{
						$putTo = $copySetting ['dir'] . '/' . $dest;
					}
					
					$results [] = self::putOjbect ( $putFrom,  $putTo );
				}
			} else {
				Log::log ( 'no copies' );
				if ($FileSetting ['type'] == 'image' && isset ( $FileSetting ['ratio'] ) && in_array ( $FileSetting ['ratio'], array (
						true,
						'W',
						'H',
						'B' 
				) )) {
					Log::log ( 'start resize' );
					;
					$ir = new \ImageResize ( $source );
					$result = $ir->resize ( $FileSetting ['width'], $FileSetting ['height'], $FileSetting ['ratio'] );
					$ir->save ( self::_getTmpDir () . '/' . $dest );
					Log::log ( 'save to ' . self::_getTmpDir () . '/' . $dest );
					$results [] = self::putOjbect ( self::_getTmpDir () . '/' . $dest, '' . $FileSetting ['dir'] . '/' . $dest );
				} else {
					$results [] = self::putOjbect ( $source, '' . $FileSetting ['dir'] . '/' . $dest );
				}
			}
			
			// if ($FileSetting ['ratio'] === true) {
			// $smallSetting = $FileSetting ['small'];
			// $normalSetting = $FileSetting ['normal'];
			
			// if ($smallSetting) {
			// $ir = new \ImageResize ( $source );
			// $result = $ir->resize ( $smallSetting ['width'], $smallSetting ['height'], $smallSetting ['ratio'] );
			// $ir->save ( self::_getTmpDir () . '/' . $dest );
			// $results [] = self::putOjbect ( self::_getTmpDir () . '/' . $dest, '' . $smallSetting ['dir'] . '/' . $dest );
			// }
			// if ($normalSetting) {
			// $ir = new \ImageResize ( $source );
			// $result = $ir->resize ( $normalSetting ['width'], $normalSetting ['height'], $normalSetting ['ratio'] );
			// $ir->save ( self::_getTmpDir () . '/' . $dest );
			// $results [] = self::putOjbect ( self::_getTmpDir () . '/' . $dest, '' . $normalSetting ['dir'] . '/' . $dest );
			// }
			// } else {
			// $results [] = self::putOjbect ( $source, $FileSetting ['dir'] . '/' . $dest );
			// }
		} else {
			$key = basename ( $dest );
		}
		// print_r ( $results );
		return $results;
	}
	private static function putOjbect($source, $dest) {
		$putOptions = array (
				'Bucket' => self::$S3BucketName,
				'Key' => $dest,
				'Body' => fopen ( $source, 'r+' ),
				'ACL' => 'public-read' 
		);
		
		$result = self::$s3->putObject ( $putOptions );
		return $result;
	}
	private static function _getTmpDir() {
		if (self::$tmpDir !== null) {
			return self::$tmpDir;
		}
		self::$tmpDir = dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) . '/tmp/image';
		return self::$tmpDir;
	}
	private static function _registerClient() {
		if (self::$s3 === null) {
			self::$s3 = S3Client::factory ( array (
					'key' => self::$S3Key,
					'secret' => self::$S3Secret,
					'region' => Region::TOKYO 
			) );
		}
	}
}