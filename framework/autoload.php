<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/18
 * Time: 12:30 下午
 */


spl_autoload_register(function ($class) {
	$tmp = explode("\\", $class);
	if (count($tmp) > 2) {
		$file = __DIR__ . '/' . strtolower($tmp[1]) . '/' . end($tmp) . '.class.php';
		if (file_exists($file)) {
			require_once $file;
		}
	}
	else {
		$file = __DIR__ . '/' . end($tmp).'.class.php';
		if (file_exists($file)) {
			require_once $file;
		}
	}
});