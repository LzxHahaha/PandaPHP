<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/21
 * Time: 10:13 下午
 */

namespace Framework\Exceptions;

use Exception;

class DBNotConnectException extends Exception {
	public function __construct() {
		parent::__construct("Database did'n connect.", 100101, null);
	}
}