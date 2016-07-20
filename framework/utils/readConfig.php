<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/20
 * Time: 10:30 下午
 */

namespace Framework\Utils;

$appConfig = include (__DIR__ . '/../../config/app.php');

define('APP_DEBUG', $appConfig['debug']);