<?php
namespace Framework\Exceptions;

use Exception;

class IllegalRoutingException extends Exception {
	public function __construct() {
		parent::__construct("Illegal Route Definition", 100401, null);
	}
}