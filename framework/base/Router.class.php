<?php
namespace Framework\Base;

use Framework\Exceptions;

/**
 * 路由类，记录路由规则，匹配相应路由
 *
 * @package Framework
 */
class Router {
	static private $routeTable = NULL;
	static private $groupRoot = NULL;
	static private $groupMiddleware = [];

	/**
	 * 插入一个路由规则
	 *
	 * @param array $route 按/分割后的URL
	 * @param string $method 请求的方法
	 * @param array $handlers 对应处理方法
	 * @param RouteTreeNode $current 当前节点
	 * @throws Exceptions\DuplicationException 重复定义路由时抛出此异常
	 * @throws Exceptions\UnknownException 未知错误
	 */
	static private function _insert(array $route, &$method, &$handlers, RouteTreeNode &$current) {
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
				Router::_insert($tail, $method, $handlers, $next);
				array_push($current->children, $next);
			}
			else {
				Router::_insert($tail, $method, $handlers, $current->children[$index]);
			}
		}
		else if ($arrLen === 1) {
			if (!$isFound) {
				$next = new RouteTreeNode($value, [$method=>$handlers], []);
				array_push($current->children, $next);
			}
			else {
				if (isset($current->children[$index]->handler[$method])) {
					throw new Exceptions\DuplicationException($value);
				}
				else {
					$current->children[$index]->handler[$method] = $handlers;
				}
			}
		}
		else {
			throw new Exceptions\UnknownException();
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
	 * @throws Exceptions\NotFoundException 未查找到节点，或查找到的节点不包含handler时抛出此异常
	 * @throws Exceptions\UnknownException 未知错误
	 */
	static private function _find(array $route, &$method, RouteTreeNode &$current, &$params) {
		$arrLen = count($route);
		if ($arrLen === 0) {
			return NULL;
		}

		$value = $route[0];

		$isMatchCurrent = $value === $current->self;
		$isParam = isset($current->self[0]) && $current->self[0] === ':';

		// 还有子节点
		if ($arrLen > 1) {
			$tail = array_slice($route, 1);

			// 当前节点能匹配
			if ($isMatchCurrent || $isParam) {
				// 遍历子节点，满足就返回，不满足就继续，都找不到就自尽
				foreach ($current->children as $child) {
					$match = self::_find($tail, $method, $child, $params);
					if ($match) {
						// 保存参数
						if ($isParam) {
							$params[substr($current->self, 1)] = $value;
						}
						return $match;
					}
				}
			}
		}
		// 没有子节点
		else if ($arrLen === 1) {
			if (($isMatchCurrent || $isParam) && isset($current->handler)) {
				if (count($current->handler) === 0) {
					throw new Exceptions\NotFoundException();
				}
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
	}

	static private function &_findOrInsert(array $route, RouteTreeNode &$current) {
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
				$node = Router::_findOrInsert($tail, $next);
				array_push($current->children, $next);
				return $node;
			}
			else {
				return Router::_findOrInsert($tail, $current->children[$index]);
			}
		}
		else if ($arrLen === 1) {
			if (!$isFound) {
				$next = new RouteTreeNode($value, [], []);
				array_push($current->children, $next);
				return $current->children[count($current->children) - 1];
			}
			else {
				return $current->children[$index];
			}
		}

		throw new Exceptions\UnknownException();
	}

	static public function init() {
		if (isset(Router::$routeTable)) {
			return;
		}

		Router::$routeTable = new RouteTreeNode("", [], []);
		Router::$groupRoot = &Router::$routeTable;
	}

	static public function routerMatch(Request $request) {
		$routeArr = explode('/', $request->route);
		if (count($routeArr) > 1 && end($routeArr) === "") {
			array_pop($routeArr);
		}

		$result = Router::_find($routeArr, $request->method, Router::$routeTable, $request->params);
		if (isset($result)) {
			return $result->handler[$request->method];
		}

		throw new Exceptions\NotFoundException();
	}

	static public function add($route, $method, &$handlers) {
		$routeArr = explode('/', $route);
		if (count($routeArr) > 1 && $routeArr[0] === "") {
			array_shift($routeArr);
		}
		if (count($routeArr) > 1 && end($routeArr) === "") {
			array_pop($routeArr);
		}


		// TODO: 增加路由合法检查

		// 当路由为"/"或""时单独处理
		if (count($routeArr) === 1 && $routeArr[0] === "") {
			if (isset(Router::$routeTable->handler[$method])) {
				throw new Exceptions\DuplicationException("/");
			}
			else {
				Router::$routeTable->handler[$method] = $handlers;
			}
			return;
		}

		$handlers = array_merge(Router::$groupMiddleware, $handlers);
		Router::_insert($routeArr, $method, $handlers, Router::$groupRoot);
	}

	static public function get($route, ...$handlers) {
		Router::add($route, 'GET', $handlers);
	}

	static public function post($route, ...$handlers) {
		Router::add($route, 'POST', $handlers);
	}

	static public function put($route, ...$handlers) {
		Router::add($route, 'PUT', $handlers);
	}

	static public function delete($route, ...$handlers) {
		Router::add($route, 'DELETE', $handlers);
	}

	static public function option($route, ...$handlers) {
		Router::add($route, 'OPTION', $handlers);
	}

	static public function group(array $public, callable $callback) {
		if (isset($public["prefix"])) {
			$routeArr = explode('/', $public["prefix"]);
			if (count($routeArr) > 1 && $routeArr[0] === "") {
				array_shift($routeArr);
			}
			if (count($routeArr) > 1 && end($routeArr) === "") {
				array_pop($routeArr);
			}
			Router::$groupRoot = &Router::_findOrInsert($routeArr, Router::$routeTable );
		}
		if (isset($public["middleware"])) {
			if (is_array($public["middleware"])) {
				Router::$groupMiddleware = $public["middleware"];
			}
			else {
				Router::$groupMiddleware = [$public["middleware"]];
			}
		}
		// Other...

		$callback();

		Router::$groupRoot = &Router::$routeTable;
		Router::$groupMiddleware = [];
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
	public function __construct($route, array $handler = [], array $children = []) {
		$this->self = $route;
		$this->handler = $handler;
		$this->children = $children;
	}
}