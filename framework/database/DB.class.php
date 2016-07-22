<?php
/**
 * Created by PhpStorm.
 * User: LzxHahaha
 * Date: 2016/7/22
 * Time: 6:42 下午
 */

namespace Framework\Database;

use Framework\Exceptions;
use PDO;
use PDOException;

class DB {
	static private $DSN = [
		'Mysql' => 'mysql:host='.APP_MYSQL_HOST.':'.APP_MYSQL_PORT.';dbname='.APP_MYSQL_DBNAME
	];

	static protected $dbConnector = null;

	static protected function checkConnect() {
		if (is_null(self::$dbConnector)) {
			throw new Exceptions\DBNotConnectException();
		}
	}

	static public function connect($update = false) {
		if ($update || is_null(self::$dbConnector)) {
			try {
				self::$dbConnector = null;
				self::$dbConnector = new PDO(
					self::$DSN[APP_DATABASE],
					APP_MYSQL_USERNAME,
					APP_MYSQL_PASSWORD,
					array(
						PDO::ATTR_PERSISTENT => true,
					)
				);
			}
			catch (PDOException $exc) {
				throw $exc;
			}
		}
	}

	static public function execute($sql, $params) {
		self::checkConnect();
		$stmt = self::$dbConnector->prepare($sql);
		return $stmt->execute($params);
	}

	static public function close() {
		self::$dbConnector = null;
	}
}