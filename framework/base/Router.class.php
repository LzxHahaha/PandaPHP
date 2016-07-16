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

	static public function init() {
		if (isset(Router::$routeTable)) {
			return;
		}

		Router::$routeTable = new RouteTreeNode('', NULL, ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], []);
	}

	static private function _insert(array $route, array $method, $handler, RouteTreeNode &$current) {

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
				if (!in_array($method, $current->method)) {
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

	static public function routerMatch(array $route, string $method, RouteTreeNode $current, &$params) {
		$result = Router::_routerMatch($route, $method, $current, $params);
		if (isset($result)) {
			return $result;
		}

		// TODO: 404 handler
		throw new Exceptions\NoFoundException();
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
	public $method = [];

	/**
	 * 构造函数
	 *
	 * @param string $route 当前节点的路由字符串
	 * @param array $method 支持的方法
	 * @param null|string|Closure $handler 对应的处理函数
	 * @param array $children 子节点，默认为空数组
	 */
	public function __construct(string $route, $handler, array $method = [], array $children = []) {
		$this->self = $route;
		$this->handler = $handler;
		$this->method = $method;
		$this->children = $children;
	}
}