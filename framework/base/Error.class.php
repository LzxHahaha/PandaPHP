<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/28
 * Time: 11:05 下午
 */

namespace Framework\Base;


class Error {
	static private $errorView = [
		404 => '<h1>404 Not Found.</h1>',
		405 => '<h1>405 Method Nor Allowed.</h1>',
		500 => '<h1>500 Server Error.</h1>'
	];

	static public function add($code, $view) {
		self::$errorView[$code] = $view;
	}

	static public function getErrorView($code, \Exception $exc = null) {
		if (!isset(self::$errorView[$code])) {
			if (is_null($exc)) {
				return self::$errorView[500];
			}
			else if (APP_DEBUG) {
				return self::$errorView[500] . '<p>' . $exc->getTrace() . '</p>';
			}
		}

		return self::$errorView[$code];
	}
}