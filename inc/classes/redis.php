<?php
declare(strict_types=1);
namespace Sakura\API;
use Redis;
class _Redis {
	private static ?Redis $_instance = null;

	private function __construct() {
		self::$_instance = new Redis();
		$temp = [
			'host'=> akina_option('redis_host'),
			'port'=> (int)akina_option('redis_port'),
			'pw'=> akina_option('redis_pw')
		];
		self::$_instance->pconnect($temp['host'], $temp['port']);
		if ($temp['pw'] != '') {
			self::$_instance->auth($temp['pw']);
		}
		if(self::$_instance === false) {
			throw new \Exception('redis connect error');
		}
	}

	public static function getRedis(): ?Redis {
		if ( ! self::$_instance ) {
			new self;
		}
		return self::$_instance;
	}
	private function __clone() {}
}