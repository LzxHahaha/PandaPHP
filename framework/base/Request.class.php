<?php
namespace Framework\Base;

class Request {
    private $route;
    private $method;
    private $headers = [];
    private $query = [];
    private $body = [];
	private $params = [];

	public $middlewareData = [];

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