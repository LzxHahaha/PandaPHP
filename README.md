# 喵

## 目录结构

```
.
├── router.php: 路由文件
├── modules: 模型类
|	└── ...
├── views: 视图模板
|	├── errors: 错误页面
|	└── ...
├── controllers: 控制器目录
|	└── ...
├── config: 配置文件目录
|	├── app.json: 项目主要配置文件
|	└── ...
├──	utils: 辅助工具
|	└── ...
└── index.php: 入口文件
```

## 计划目标

- [ ] 单入口处理路由
- [ ] 自动匹配控制器对应函数
- [ ] 中间件
- [ ] 简易ORM
- [ ] 简易模板引擎

## 路由

请求都从`index.php`开始，获取请求头，然后丢给路由类处理。

设计一个路由类，能按方法和路由进行匹配，类似`Router.(all|get|post|...)(route, [, middleware], action)`。

`middleware`的参数再定。

用PathInfo来实现 `index.php/route` 的形式，路由的格式为 `/route[(/:param)(/route)]*[?query][#anchor]`，路由需要能按正则匹配。

还要实现一些方法，比如统一前缀、统一中间件的，之后再说。

## 之后的还没想好