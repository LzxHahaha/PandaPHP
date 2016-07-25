<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/25
 * Time: 11:29 下午
 */

function error(Exception $exc, $code = NULL) {
	if (is_null($code)) {
		$code = $exc->getCode();
	}

	echo '<h1>'.$code.' '.$exc->getMessage().'</h1>';

	if (APP_DEBUG) {
		// TODO: 实现优美的错误显示
		var_dump($exc->getTrace());
	}
}