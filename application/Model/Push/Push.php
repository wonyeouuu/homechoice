<?php

namespace Model\Push;

use Model\Error\Error;
use Model\Db\Db;

class Push {
	public static $gcmResults = array (
			'success' => 0,
			'failure' => 0 
	);
	public static function SendMessage($title, $infors, $options = array()) {
		if ($options ['AddCommunity']) {
			$CommunityNameList = array ();
			foreach ( $infors as $uInfor ) {
				if ($uInfor ['Platform'] == '1') {
					$CommunityNameList [$uInfor ['CommunityName']] [] = $uInfor;
				}
			}
			
			// print_r($CommunityNameList);
			
			foreach ( $CommunityNameList as $CommunityName => $uInforRows ) {
				$gcmList = array ();
				foreach ( $uInforRows as $uInforRow ) {
					$gcmList [] = $uInforRow;
				}
				if (! empty ( $CommunityName )) {
					$newTitle = '[' . $CommunityName . ']' . $title;
				}
				self::sendGcmByInfors ( $newTitle, $gcmList, $options );
			}
		} else {
			
			$gcmList = array ();
			foreach ( $infors as $uInfor ) {
				if ($uInfor ['Platform'] == '1') {
					$gcmList [] = $uInfor;
				}
			}
			
			self::sendGcmByInfors ( $title, $gcmList, $options );
		}
		return;
	}
	public static function sendGcmByInfors($title, $infors, $options = array()) {
		$url = 'https://android.googleapis.com/gcm/send';
		
		$message = array (
				"title" => $title 
		);
		if ($options ['Url']) {
			$message ['Url'] = $options ['Url'];
		}
		
		$registatoin_ids = array ();
		foreach ( $infors as $infor ) {
			if (! empty ( $infor ['RegistrationID'] )) {
				$registatoin_ids [] = $infor ['RegistrationID'];
			}
		}
		
		$fields = array (
				'registration_ids' => $registatoin_ids,
				'data' => $message 
		);
		
		$headers = array (
				'Authorization: key=AIzaSyDI8LTVZVZOSvFasP0f3AIYC7K8YM5ajoc',
				'Content-Type: application/json' 
		);
		// Open connection
		$ch = curl_init ();
		
		// Set the url, number of POST vars, POST data
		curl_setopt ( $ch, CURLOPT_URL, $url );
		
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		
		// Disabling SSL Certificate support temporarly
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode ( $fields ) );
		
		// Execute post
		$result = curl_exec ( $ch );
		if ($result === FALSE) {
			Error::warning ( 'CURL執行失敗' );
			return false;
		}
		
		$resultArr = json_decode ( $result, true );
		
		self::$gcmResults ['success'] += $resultArr ['success'];
		self::$gcmResults ['failure'] += $resultArr ['failure'];
		
		// Close connection
		curl_close ( $ch );
		return $result;
	}
}