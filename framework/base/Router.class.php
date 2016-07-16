<?php
namespace Framework;

include_once '../exceptions/NoFoundException.php';
include_once '../exceptions/MethodNotAllowedException.php';

use Closure;

/**
 * 路由类，记录路由规则，匹配相应路由
 *
 * @package Framework
 */
class Router {
	static private $routeTable = NULL;

	static public function getRouterTable() {
		return Router::$routeTable;
	}

	static public function init() {
		if (isset(Router::$routeTable)) {
			return;
		}

		Router::$routeTable = new RouteTreeNode('', [], []);
	}

	static private function _insert(array $route, string &$method, &$handler, RouteTreeNode &$current) {
		$arrLen = count($route);
		$value = $arrLen > 0 ? $route[0] : "";
		$isFound = false;
		$index = 0;

		$childrenLen = count($current->children);
		for (; $index < $childrenLen; ++$index) {
			if ($current->children[$index]->self === $value) {
				$isFound = true;
				break;
			}
		}

		if ($arrLen > 1) {
			$tail = array_slice($route, 1);

			// 当重复时递归检查，否则直接把后面的都加进去
			if (!$isFound) {
				$next = new RouteTreeNode($value, [], []);
				Router::_insert($tail, $method, $handler, $next);
				array_push($current->children, $next);
			}
			else {
				Router::_insert($tail, $method, $handler, $current->children[$index]);
			}
		}
		else if ($arrLen === 1) {
			if (!$isFound) {
				$next = new RouteTreeNode($value, [$method=>$handler], []);
				array_push($current->children, $next);
			}
			else {
				if (isset($current->children[$index]->handler[$method])) {
					// TODO: throw...
				}
				else {
					$current->children[$index]->handler[$method] = $handler;
				}
			}
		}
		else {
			// TODO: throw...
		}
	}

	/**
	 * 深度优先遍历路由规则树，返回匹配到的节点
	 *
	 * @param array $route 按/分割后的URL
	 * @param string $method 请求的方法
	 * @param RouteTreeNode $current 当前节点，应从根节点开始
	 * @param array $params 记录URL中的params
	 * @return RouteTreeNode|null 返回匹配的节点，若查找失败则返回NULL
	 * @throws Exceptions\MethodNotAllowedException 当不包含请求的方法时抛出此异常
	 */
	static private function _routerMatch(array $route, string &$method, RouteTreeNode &$current, &$params) {
		$arrLen = count($route);
		$value = $arrLen > 0 ? $route[0] : "";

		$isMatchCurrent = $value === $current->self;
		$isParam = isset($current->self[0]) && $current->self[0] === ':';

		// 还有子节点
		if ($arrLen > 1) {
			$tail = array_slice($route, 1);

			// 当前节点能匹配
			if ($isMatchCurrent || $isParam) {
				// 遍历子节点，满足就返回，不满足就继续，都找不到就自尽
				foreach ($current->children as $child) {
					$match = self::_routerMatch($tail, $method, $child, $params);
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
				if (!isset($current->handler[$method])) {
					throw new Exceptions\MethodNotAllowedException();
				}
				if ($isParam) {
					$params[substr($current->self, 1)] = $value;
				}
				return $current;
			}

			return NULL;
		}
		else {
			return NULL;
		}
	}

	static public function routerMatch(string $route, string $method, &$params) {
		$routeArr = explode('/', $route);

		array_unshift($routeArr, "");
		$result = Router::_routerMatch($routeArr, $method, Router::$routeTable, $params);
		if (isset($result)) {
			return $result;
		}

		// TODO: 404 handler
		throw new Exceptions\NoFoundException();
	}

	static public function add(string $route, string $method, &$handler) {
		$routeArr = explode('/', $route);

		// TODO: 增加路由合法检查

		Router::_insert($routeArr, $method, $handler, Router::$routeTable);
	}

	static public function get(string $route, $handle) {
		Router::add($route, 'GET', $handle);
	}

	static public function post(string $route, $handle) {
		Router::add($route, 'POST', $handle);
	}
}

/**
 * 路由规则树中节点的数据结构
 *
 * @package Framework
 */
class RouteTreeNode {
	public $self;
	public $handler;
	public $children = [];

	/**
	 * 构造函数
	 *
	 * @param string $route 当前节点的路由字符串
	 * @param array $handler [method=>func] 请求方法对应的函数
	 * @param array $children 子节点，默认为空数组
	 */
	public function __construct(string $route, array $handler = [], array $children = []) {
		$this->self = $route;
		$this->handler = $handler;
		$this->children = $children;
	}
}