<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/20
 * Time: 8:53 下午
 */

namespace Framework\Exceptions;

use Exception;

class DuplicationException extends Exception {
	public function __construct($route) {
		parent::__construct("Duplication Route Definition: [".$route."].", 100402, null);
	}
}