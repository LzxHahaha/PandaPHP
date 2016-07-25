<?php
namespace Framework;

use Framework\Base\Request;
use Framework\Base\Response;
use Framework\Base\Router;

require_once 'utils/readConfig.php';
require_once 'autoload.php';
require_once 'utils/getallheaders.php';
require_once 'utils/error.php';

$request = Request::initFromRequest();
$response = new Response();

try {
	Router::init();
	require_once '../router.php';
	$handlers = Router::routerMatch($request);

	foreach ($handlers as $handler) {
		if (is_string($handler)) {
			$tmp = explode('@', $handler);
			$class = "Controller\\" . $tmp[0];
			$function = $tmp[1];
			(new $class)->$function($request, $response);
		}
		else {
			$handler($request, $response);
		}
	}
}
catch (\Exception $exc) {
	$code = $exc->getCode();
	// 大于100 000 的都是框架错误，其他的尽可能与HTTP相同
	if ($code < 100000) {
		error($exc);
	}
	else {
		error($exc, 500);
	}
}
