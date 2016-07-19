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

Router::get('/test/:param',
	function ($request) {
	    echo "I'll kill this request!<br/>";
		$request->end();
    },
	function ($request) { echo 'GET hello ' . $request->params('param'); }
);

Router::post('/test/:param', function ($request) {
	echo 'param: ', $request->params('param'),
	'<br>', 'body: ', $request->body('body'),
	'<br>', 'query: ', $request->query('query');
});