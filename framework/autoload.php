<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/18
 * Time: 12:30 下午
 */


spl_autoload_register(function ($class) {
	$tmp = explode("\\", $class);
	if ($tmp[0] === 'Framework' && count($tmp) === 3) {
		$file = __DIR__ . '/' . strtolower($tmp[1]) . '/' . end($tmp) . '.class.php';
		if (file_exists($file)) {
			require_once $file;
		}
	}
});