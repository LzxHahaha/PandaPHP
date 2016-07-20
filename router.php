<?php

use Framework\Base\Router;

Router::get('/', function () {
	echo "Hello get!";
});

Router::post('/', function () {
	echo "Hello post!";
});

Router::get('/hello/:word',
	function () { echo 'middleware is work!<br/>'; },
	function ($request) { echo 'GET hello ' . $request->params('word'); }
);

Router::get('/test',
	function ($request) {
	    echo "I'll kill this request!<br/>";
		$request->end();
    },
	function ($request) { echo 'GET hello ' . $request->params('param'); }
);

Router::group(["prefix"=>"/prefix"], function () {
	Router::get("foo", function () { echo "/prefix/foo"; });
});

Router::group(["middleware"=>function () { echo "middleware"; }], function () {
	Router::get("foo", function () { echo "/foo"; });
});
