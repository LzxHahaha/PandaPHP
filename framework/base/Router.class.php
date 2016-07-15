<?php
namespace Framework;

use Closure;
use Framework\Exceptions;

/**
 * 路由类，记录路由规则，匹配相应路由
 * @package Framework
 */
class Router {
	private static $rootRouter;

	private static function add(string $route, string $method, Closure $handler) {

	}

	/**
	 * 深度优先遍历路由规则树，返回匹配到的节点
	 *
	 * @param array $route 按/分割后的URL
	 * @param RouteTreeNode $current 当前节点，应从根节点开始
	 * @param $params 记录URL中的params
	 * @return RouteTreeNode|null 返回匹配的节点，若查找失败则返回NULL
	 */
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

/**
 * 路由规则树中节点的数据结构
 *
 * @package Framework
 */
class RouteTreeNode {
	public $self;
	public $children = [];
	public $handler;

	/**
	 * 构造函数
	 *
	 * @param string $route 当前节点的路由字符串
	 * @param $handler 对应的处理，可以为NULL/String/Closure
	 * @param array $children 子节点，默认为空数组
	 */
	public function __construct(string $route, $handler, array $children = []) {
		$this->self = $route;
		$this->handler = $handler;
		$this->children = $children;
	}
}