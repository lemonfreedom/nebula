<?php

namespace Nebula;

class Router
{
    // 路由表
    private static $routingTable = [
        [
            'regx' => '/^[\/]?$/',
            'widget' => '\\Itell\\Widget\\Index',
            'action' => 'render',
        ],
        [
            'regx' => '/^\/action\/([_0-9a-zA-Z-]+)[\/]?$/',
            'widget' => '\\Itell\\Widget\\Action',
            'params' => ['action'],
        ],
    ];

    /**
     * 路由分发
     *
     * @return void
     */
    public static function dispatch()
    {
        $pathInfo = Request::getInstance()->pathinfo;

        // 路由解析
        foreach (self::$routingTable as $route) {
            if (preg_match($route['regx'], $pathInfo, $matches)) {
                var_dump($matches);
                // 解析参数
                if (!empty($route['params'])) {
                    unset($matches[0]);
                    $params = array_combine($route['params'], $matches);
                }

                // 实例化组件
                // $widget = Widget::factory($route['widget'], $params);

                // 执行组件方法
                if (isset($route['action'])) {
                    // $widget->{$route['action']}();
                }
            } else {
                echo 1;
            }
        }
    }

    /**
     * 设置路由器配置
     *
     * @param array $routes 配置信息
     */
    public static function setRoutes($routes)
    {
        self::$routingTable = $routes;
    }
}
