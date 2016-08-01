<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/24
 * Time: 9:51 上午
 */

namespace Controller;


use Model\Test;

class TestController {
	public function all($req, $res) {
		return $res->json(Test::all());
	}
}