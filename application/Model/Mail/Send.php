<?php

namespace Model\Mail;

require_once dirname ( dirname ( dirname ( ( __FILE__ ) ) ) ) . '/aws-autoloader.php';
require_once dirname ( dirname ( dirname ( dirname ( __FILE__ ) ) ) ) . '/class/ImageResize.php';

use \Aws\Ses\SesClient;
use \Aws\Common\Enum\Region;

class Send {
	private static $ses;
	private static $SesKey = 'AKIAIACVFUPOZPPFHQAQ';
	private static $SesSecret = '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';
	public static $source = 'ec@umesh.tw';
	public static function sendMail($subject, $content, $to = array(), $options = array()) {
		self::_registerClient ();
		$client = self::$ses;
		
		$options = self::collateOptions ( $options );
		
		$toAddess = array_values ( $to );
		
		$msg = array ();
		$msg ['Source'] = $options ['source'];
		// ToAddresses must be an array
		$msg ['Destination'] ['ToAddresses'] = $toAddess;
		
		$msg ['Message'] ['Subject'] ['Data'] = $subject;
		$msg ['Message'] ['Subject'] ['Charset'] = $options ['Charset'];
		
		$msg ['Message'] ['Body'] ['Html'] ['Data'] = $content;
		$msg ['Message'] ['Body'] ['Html'] ['Charset'] = $options ['Charset'];
		
		$result = $client->sendEmail ( $msg );
		return $result;
		
		// save the MessageId which can be used to track the request
		$msg_id = $result->get ( 'MessageId' );
		return $result;
		echo ( "MessageId: $msg_id" );
		
		// view sample output
		print_r ( $result );
	}
	private static function collateOptions($options) {
		if (! $options ['source']) {
			$options ['source'] = self::$source;
		}
		
		if (! $options ['Charset']) {
			$options ['Charset'] = 'UTF-8';
		}
		
		return $options;
	}
	private static function _registerClient() {
		if (self::$ses === null) {
			self::$ses = SesClient::factory ( array (
					'key' => self::$SesKey,
					'secret' => self::$SesSecret,
					'region' => Region::US_WEST_2 
			) );
		}
	}
}