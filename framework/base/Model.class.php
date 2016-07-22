<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/21
 * Time: 9:46 下午
 */

namespace Framework\Base;

use Framework\Database\DB;

// TODO: 设计接口

class Model {
	protected $table;

	protected $hidden = [];

	public function __construct($table) {
		$this->table = $table;
	}

	public function all() {
		DB::connect();

		// TODO: 增加hidden筛选
		$result = DB::execute('select * from '.$this->table);
		return $result;
	}
}