<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/20
 * Time: 8:55 下午
 */

namespace Framework\Exceptions;

use Exception;

class UnknownException extends Exception {
	public function __construct() {
		parent::__construct("Unknown Exception.", 100001, null);
	}
}