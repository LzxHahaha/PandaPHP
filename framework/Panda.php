<?php
namespace Framework;

use Framework\Base\Request;
use Framework\Base\Response;
use Framework\Base\Router;

require_once 'autoload.php';
require_once 'utils/getallheaders.php';
require_once 'utils/readConfig.php';

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

$request = new Request(
    $route,
    $method,
    $headers,
    $_GET,
    $body
);
$response = new Response();

try {
	Router::init();
	require_once '../router.php';
	$handlers = Router::routerMatch($request);

	foreach ($handlers as $handler) {
		$handler($request, $response);
	}
}
catch (\Exception $exc) {
	echo '<h1>' . $exc->getCode() . ' ' . $exc->getMessage() . '</h1>';
	if (APP_DEBUG) {
		var_dump($exc->getTrace());
	}
}
