# 喵

## 目录结构

```
.
├── modules: 模型类
|	└── ...
├── views: 视图模板
|	├── errors: 错误页面
|	└── ...
├── controllers: 控制器目录
|	└── ...
├── config: 配置文件目录
|	├── app.php: 项目主要配置文件
|	└── ...
├── utils: 辅助工具
|	└── ...
├──	framework: 库代码
|	├── base: 基础数据结构定义
|	├── exceptions: 常用异常定义
|	├── test: 测试
|	└── ...
├── public: 辅助工具
|	├── index.php: 入口文件
|	└── ...
└── router.php: 路由文件
```

## 计划目标

- [x] 单入口
- [ ] 路由
    - [ ] 路由定义
    - [x] 路由匹配
- [ ] 自动匹配控制器对应函数
- [ ] 中间件
- [ ] 简易ORM
- [ ] 简易模板引擎

## 路由

请求都从`index.php`开始，获取请求头，然后丢给路由类处理。

设计一个路由类，能按方法和路由进行匹配，类似`Router.(all|get|post|...)(route[, middleware], action)`。

`middleware`的参数再定。

用PathInfo来实现 `index.php/route` 的形式，路由的格式为 `/route[(/:param)(/route)]*[?query][#anchor]`，路由需要能按正则匹配。

还要实现一些方法，比如统一前缀、统一中间件的，之后再说。

## 之后的还没想好