<?php
namespace Framework\Exceptions;

use Exception;

class IllegalRoutingException extends Exception {
	public function __construct($route) {
		parent::__construct("Illegal Route Definition: [".$route."].", 100401, null);
	}
}