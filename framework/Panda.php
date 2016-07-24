<?php
namespace Framework;

use Framework\Base\Request;
use Framework\Base\Response;
use Framework\Base\Router;

require_once 'utils/readConfig.php';
require_once 'autoload.php';
require_once 'utils/getallheaders.php';

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
	echo '<h1>' . $exc->getCode() . ' ' . $exc->getMessage() . '</h1>';
	if (APP_DEBUG) {
		// TODO: 实现优美的错误显示
		var_dump($exc->getTrace());
	}
}
