<?php

namespace Model\Error;

class Error {
	
	/**
	 * the errors stack
	 *
	 * @var array
	 */
	private static $errors = array ();
	private static $notices = array ();
	private static $warnings = array ();
	
	/**
	 * clear the errors stack
	 */
	function clear() {
		self::$errors = array ();
	}
	public static function notice($n) {
		self::$notices [] = $n;
	}
	public static function warning($n) {
		self::$warnings [] = $n;
	}
	public static function error($n) {
		self::$errors [] = $n;
	}
	
	/**
	 * check to see if any errors are set
	 *
	 * @return bool
	 */
	static function hasErrors() {
		if (! empty ( self::$errors )) {
			return true;
		}
	}
	static function getFeedBackMsg(){
		if(!empty(self::$warnings )){
			return self::getWarnings();
		}elseif(!empty(self::$errors)){
			return self::getErrors();
		}
		return '';
	}
	static function getWarnings() {
		if (! empty ( self::$warnings )) {
			if (is_array ( self::$warnings ))
				return implode ( ',', self::$warnings );
		}
	}
	static function getErrors() {
		if (! empty ( self::$errors )) {
			if (is_array ( self::$errors ))
				return implode ( ',', self::$errors );
		}
	}
	
	/**
	 * check to see if any notices are set
	 *
	 * @return bool
	 */
	static function hasNotices() {
		if (! empty ( self::$notices )) {
			return true;
		}
	}
	
	/**
	 * check to see if any warnings are set
	 *
	 * @return bool
	 */
	static function hasWarnings() {
		if (! empty ( self::$warnings )) {
			return true;
		}
	}
	
	/**
	 * get the errors stack
	 *
	 * @return string
	 */
	static function get() {
		return array (
				'notice' => self::$errors,
				'warning' => self::$warnings,
				'error' => self::$warnings 
		);
	}
}