<?php
namespace Framework;

class Request {
    private $route;
    private $method;
    private $headers = [];
    private $query = [];
    private $body = [];
	private $params = [];

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

    public function headers($key) {
    	if (array_key_exists($key, $this->headers)) {
    		return $this->headers[$key];
	    }
    }

    public function body($key) {
    	if (array_key_exists($key, $this->body)) {
    		return $this->body[$key];
	    }
    }

    public function params($key) {
    	if (array_key_exists($key, $this->params)) {
    		return $this->params[$key];
	    }
    }

    public function query($key) {
    	if (array_key_exists($key, $this->query)) {
    		return $this->query[$key];
	    }
    }
}