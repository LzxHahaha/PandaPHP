<?php
namespace Framework\Base;

use Framework\Utils;

class Request {
    private $route;
    private $method;
    private $headers = [];
    private $query = [];
    private $body = [];
	private $params = [];

	public $middlewareData = [];

	static public function initFromRequest() {
		$route = $_SERVER['PATH_INFO'] === 'PATH_INFO' ? "/" : $_SERVER['PATH_INFO'];
		$method = $_SERVER["REQUEST_METHOD"];
		$headers = Utils\getallheaders();
		$contentType = isset($headers["CONTENT-TYPE"]) ? $headers["CONTENT-TYPE"] : NULL;
		$body = [];

		if (!is_null($contentType)) {
			// 根据Content Type解析body
			if (strcasecmp($method, 'POST') == 0) {
				switch ($contentType) {
					case 'application/x-www-form-urlencoded':
						$body = $_POST;
						break;
					case 'application/json':
						$body = (array)json_decode(file_get_contents("php://input"));
						break;
					default:
						// 其他的先不管
						$body = $_POST;
						break;
				}
			}
		}

		return new Request(
			$route,
			$method,
			$headers,
			$_GET,
			$body
		);
	}

    public function __construct($route, $method, Array $headers = [], Array $query = [], Array $body = []) {
        $this->route = $route;
        $this->method = $method;
        $this->headers = $headers;
        $this->query = $query;
        $this->body = $body;
    }

    public function &__GET($name) {
        if (isset($this->$name)) {
            return $this->$name;
        }
        else {
            return NULL;
        }
    }

    public function headers($key, $defaultValue = NULL) {
    	if (array_key_exists($key, $this->headers)) {
    		return $this->headers[$key];
	    }
	    else {
		    return $defaultValue;
	    }
    }

    public function body($key, $defaultValue = NULL) {
    	if (array_key_exists($key, $this->body)) {
    		return $this->body[$key];
	    }
	    else {
	    	return $defaultValue;
	    }
    }

    public function params($key, $defaultValue = NULL) {
    	if (array_key_exists($key, $this->params)) {
    		return $this->params[$key];
	    }
	    else {
		    return $defaultValue;
	    }
    }

    public function query($key, $defaultValue = NULL) {
    	if (array_key_exists($key, $this->query)) {
    		return $this->query[$key];
	    }
	    else {
		    return $defaultValue;
	    }
    }

    public function end() {
    	exit;
    }
}