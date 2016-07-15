<?php
namespace Framework;

class Request {
    private $route;
    private $method;
    private $headers;
    private $query;
    private $body;

    public function __construct(string $route, string $method, Array $headers = [], Array $query = [], Array $body = []) {
        $this->route = $route;
        $this->method = $method;
        $this->headers = $headers;
        $this->query = $query;
        $this->body = $body;
    }

    public function __GET($name) {
        if (isset($this->$name)) {
            return $this->$name;
        }
        else {
            return NULL;
        }
    }
}