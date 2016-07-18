<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/18
 * Time: 12:30 下午
 */


spl_autoload_register(function ($class) {
	$tmp = explode("\\", $class);
	$file = __DIR__ . '/base/'.end($tmp).'.class.php';
	if (file_exists($file)) {
		require_once $file;
	}
}, false);

spl_autoload_register(function ($class) {
	$tmp = explode("\\", $class);
	$file = __DIR__ . '/exceptions/'.end($tmp).'.class.php';
	if (file_exists($file)) {
		require_once $file;
	}
}, false);