<?php
namespace Framework\Test;

/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/15
 * Time: 11:05 下午
 */

use Framework\Router;
use Framework\RouteTreeNode;

include_once '../base/Router.class.php';

// Router match test;
(function () {
	$params = [];
	$tree = [
		new RouteTreeNode('a', function(){echo 'a';}, [
			new RouteTreeNode('b', NULL, [
				new RouteTreeNode(':foo', function($foo){echo '/a/b/:foo, foo=', $foo;}),
			]),
			new RouteTreeNode('c', NULL, [
				new RouteTreeNode(':foo', NULL, [
					new RouteTreeNode(':bar', function($foo, $bar){echo '/a/:foo/:bar, foo=', $foo, "&bar=", $bar;})
				]),
			]),
			new RouteTreeNode(':bar', NULL, [
				new RouteTreeNode('d', function($bar){echo '/a/:bar/d, bar=', $bar;})
			])
		])
	];

	echo "Test 1: [/a/b/1] => ";
	$node = Router::routerMatch(['a', 'b', '1'], $tree[0], $params);
	if (isset($node) && isset($node->handler)) {
		($node->handler)($params['foo']);
	}
	$params = [];
	echo "\n\n";

	echo "Test 2: [/a/c/1/2] => ";
	$node = Router::routerMatch(['a', 'c', '1', '2'], $tree[0], $params);
	if (isset($node) && isset($node->handler)) {
		($node->handler)($params['foo'], $params['bar']);
	}
	$params = [];
	echo "\n\n";

	echo "Test 3: [/a/1/d] => ";
	$node = Router::routerMatch(['a', '1', 'd'], $tree[0], $params);
	if (isset($node) && isset($node->handler)) {
		($node->handler)($params['bar']);
	}
	$params = [];
	echo "\n\n";

	echo "Test 4: [/a/b/1] => ";
	$node = Router::routerMatch(['a', 'b', '1'], $tree[0], $params);
	if (isset($node) && isset($node->handler)) {
		($node->handler)($params['foo']);
	}
	$params = [];
	echo "\n\n";

	echo "Test 5: [/a] => ";
	$node = Router::routerMatch(['a'], $tree[0], $params);
	if (isset($node) && isset($node->handler)) {
		($node->handler)();
	}
	$params = [];
	echo "\n\n";
})();