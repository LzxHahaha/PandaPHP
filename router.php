<?php

use Framework\Base\Router;

Router::get('/', function ($req, $res) {
	$res->send("Hello get!");
});

Router::post('/', function ($req, $res) {
	$res->json(["status" => 1, "message" => "success"]);
});

Router::get('/hello/:word',
	function ($req, $res) { $res->send('middleware is work!<br/>'); },
	function ($req, $res) { $res->send('GET hello ' . $req->params('word')); }
);

Router::get('/test',
	function ($req) {
	    echo "I'll kill this request!<br/>";
		$req->end();
    },
	function ($req, $res) { $res->send('GET hello ' . $req->params('param')); }
);

Router::group(["prefix"=>"/prefix"], function () {
	Router::get("foo", function ($req, $res) { $res->send("/prefix/foo"); });
});

Router::group(["middleware"=>function ($req, $res) { $res->send("middleware"); }], function () {
	Router::get("foo", function ($req, $res) { $res->send("/foo"); });
});
