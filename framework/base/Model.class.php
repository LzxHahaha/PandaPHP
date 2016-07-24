<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/21
 * Time: 9:46 下午
 */

namespace Framework\Base;

use Framework\Database\DB;
use PDO;

// TODO: 设计接口

class Model {
	protected $table;
	protected $fillable = [];
	protected $key = null;

	public function __construct() {
		if (is_null($this->table)) {
			$tmp = explode("\\", static::class);
			$this->table = end($tmp);
		}
	}

	static public function all() {
		DB::connect();

		$model = new static();
		$query = DB::execute('select * from '.$model->table, [], PDO::FETCH_CLASS, static::class);

		return $query;
	}
}