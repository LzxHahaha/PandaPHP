<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/25
 * Time: 11:29 下午
 */

use Framework\Base\Error;

/**
 * @param Exception $exc 异常
 * @param null $code 自定义错误码，当没有提供时则使用异常中的错误码
 */
function error(Exception $exc, $code = NULL) {
	if (is_null($code)) {
		$code = $exc->getCode();
	}

	echo Error::get($code);

	if (APP_DEBUG) {
		// TODO: 实现优美的错误显示
		var_dump($exc->getTrace());
	}
}