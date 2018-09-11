<?php
class Config {
	private static $_data;

	public function set($key, $value) {
		self::$_data[$key] = $value;
	}  

	public function get($key) {
		return self::$_data[$key];
	}
}