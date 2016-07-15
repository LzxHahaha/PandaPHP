<?php
namespace Framework;

use Closure;
use Framework\Exceptions;

class Router {
	private static $rootRouter;

	private static function add(string $route, string $method, Closure $handler) {

	}

	static public function routerMatch(array $route, RouteTreeNode $current, &$params) {
		$value = $route[0];
		$arrLen = count($route);

		$isMatchCurrent = $value === $current->self;
		$isParam = $current->self[0] === ':';

		// 还有子节点
		if ($arrLen > 1) {
			$tail = array_slice($route, 1);

			// 当前节点能匹配
			if ($isMatchCurrent || $isParam) {
				// 遍历子节点，满足就返回，不满足就继续，都找不到就自尽
				foreach ($current->children as $child) {
					$match = self::routerMatch($tail, $child, $params);
					if ($match) {
						// 保存参数
						if ($isParam) {
							$params[substr($current->self, 1)] = $value;
						}
						return $match;
					}
				}
			}
			return NULL;
		}
		// 没有子节点
		else if ($arrLen === 1) {
			if (($isMatchCurrent || $isParam) && isset($current->handler)) {
				if ($isParam) {
					$params[substr($current->self, 1)] = $value;
				}
				return $current;
			}

			return NULL;
		}
		else {
			// TODO: 改为抛异常
			return NULL;
		}
	}
}

class RouteTreeNode {
	public $self;
	public $children = [];
	public $handler;

	public function __construct(string $route, $handler, array $children = []) {
		$this->self = $route;
		$this->handler = $handler;
		$this->children = $children;
	}
}