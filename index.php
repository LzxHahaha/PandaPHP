<?php
namespace Framework;

require_once('./framework/base/Request.class.php');

$method = $_SERVER["REQUEST_METHOD"];
$headers = getallheaders();
$contentType = $headers["Content-Type"];
$body = [];

// 根据Content Type解析body
if (strcasecmp($method, 'POST') == 0 && isset($contentType)) {
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

$request = new Request(
    $_SERVER['PATH_INFO'],
    $method,
    $headers,
    $_GET,
    $body
);

