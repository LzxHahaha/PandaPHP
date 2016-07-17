<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/16
 * Time: 10:16 上午
 */

namespace Framework\Exceptions;

use Exception;

class MethodNotAllowedException extends Exception {
	public function __construct() {
		parent::__construct("Method Not Allowed.", 405, null);
	}
}