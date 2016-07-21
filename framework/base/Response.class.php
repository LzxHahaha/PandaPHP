<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/21
 * Time: 2:57 下午
 */

namespace Framework\Base;


class Response {
	public function __construct() {
		header('x-powered-by: Panda PHP 0.1');
	}

	public function header($key, $value, $replace = true) {
		if (!$replace && key_exists($key, headers_list())) {
			return;
		}
		header($key, $value);
	}

	public function removeHeader($key) {
		header_remove($key);
	}

	public function view($name) {
		throw new \Exception('Not implement.');
	}

	public function send($content) {
		print_r($content);
	}

	public function json($obj) {
		echo json_encode($obj);
	}

	public function redirect($url) {
		header('Location: ' . $url);
		exit;
	}
}