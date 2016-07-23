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
	static private $instance = null;

	protected $table;
	protected $hidden = [];

	protected static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	static public function all() {
		DB::connect();

		$model = new static();
		$query = DB::execute('select * from ' . $model->table);

		if (isset($model->hidden) && count($model->hidden)) {
			foreach ($query as $item) {
				foreach ($model->hidden as $key) {
					unset($item[$key]);
				}
			}
		}

		return $query;
	}

	static public function select($columns) {
		if (is_null($columns) || count($columns) === 0) {
			return [];
		}

		$model = new static();
		$columns = array_diff($columns, $model->hidden);
		if (count($columns) === 0) {
			return [];
		}

		$query = DB::execute('select ' . join(',', $columns) . ' from ' . $model->table);
		return $query;
	}
}