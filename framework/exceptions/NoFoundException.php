<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/16
 * Time: 9:57 上午
 */

namespace Framework\Exceptions;

use Exception;

class NoFoundException extends Exception {
	public function __construct() {
		parent::__construct("Not Found", 404, null);
	}
}