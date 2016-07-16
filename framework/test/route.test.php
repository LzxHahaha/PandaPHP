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
use Framework\RouteTreeNode;

// Router match test;
(function () {
	$root = new RouteTreeNode('a', function () { echo 'a'; }, ['GET'], [
		new RouteTreeNode('b', NULL, [], [
			new RouteTreeNode(':foo', function ($params) { echo '/a/b/:foo, foo=', $params["foo"]; }, ['GET']),
		]),
		new RouteTreeNode('c', NULL, [], [
			new RouteTreeNode(':foo', NULL, [], [
				new RouteTreeNode(':bar', function ($params) { echo '/a/c/:foo/:bar, foo=', $params["foo"], "&bar=", $params["bar"]; }, ['GET'])]),
		]),
		new RouteTreeNode(':bar', NULL, [], [
			new RouteTreeNode('d', function ($params) { echo '/a/:bar/d, bar=', $params["bar"]; }, ['GET'])
		])
	]);

	$testFunc = (function ($counter, $title, $root, $route, $method) {
		try {
			$params = [];
			echo "Test ", $counter, ": ", $title;
			$node = Router::routerMatch($route, $method, $root, $params);
			if (isset($node->handler)) {
				($node->handler)($params);
			}
		}
		catch (Exception $exc) {
			echo $exc->getMessage();
		}
		echo "\n\n";
	});

	$counter = 1;
	$testFunc($counter++, "[/a/b/1] => ", $root, ['a', 'b', '1'], 'GET');
	$testFunc($counter++, "[/a/c/1/2] => ", $root, ['a', 'c', '1', '2'], 'GET');
	$testFunc($counter++, "[/a/1/d] => ", $root, ['a', '1', 'd'], 'GET');
	$testFunc($counter++, "[/a] =>", $root, ['a'], 'GET');
	$testFunc($counter++, "[/a/b/b/b] => ", $root, ['a', 'b', 'b', 'b'], 'GET');
	$testFunc($counter++, "[/b] => ", $root, ['b'], 'GET');
	$testFunc($counter++, "[/a] => ", $root, ['a'], 'POST');
})();