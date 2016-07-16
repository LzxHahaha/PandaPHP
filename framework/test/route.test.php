<?php
namespace Framework\Test;

/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/15
 * Time: 11:05 下午
 */

include_once '../base/Router.class.php';

use Exception;
use Framework\Router;

(function () {
	Router::init();

	Router::get('/', function () {echo 'GET /';});
	Router::get('/a', function () {echo 'GET /a';});
	Router::get('/a/b', function () {echo 'GET /a/b';});
	Router::get('/b/:id', function () {echo 'GET /b/:c';});
	Router::post('/a', function () {echo 'POST /a';});
	Router::post('/a/b', function () {echo 'POST /a/b';});

	$testFunc = (function ($counter, $route, $method) {
		try {
			$params = [];
			echo "Test ", $counter, ": ", $method, " [", $route, "] => ";
			$node = Router::routerMatch($route, $method, $params);
			if (isset($node->handler)) {
				($node->handler[$method])($params);
			}
		}
		catch (Exception $exc) {
			echo $exc->getMessage();
		}
		echo "\n\n";
	});

	$counter = 1;
	$testFunc($counter++, '/', 'GET');
	$testFunc($counter++, '/a', 'POST');
	$testFunc($counter++, '/a', 'GET');
	$testFunc($counter++, '/a/b', 'GET');
	$testFunc($counter++, '/a/b', 'POST');
	$testFunc($counter++, '/a/b/c', 'GET');
	$testFunc($counter++, '/b/1', 'GET');
	$testFunc($counter++, '/b/1', 'POST');

	echo "Router match done.\n==========\n\n";
})();