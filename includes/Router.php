<?php

namespace Nebula;

class Router
{
    /**
     * 路由组件映射
     *
     * @var array
     */
    private static $routerWidgetMap = [
        'index' => [
            // 映射组件
            'widget' => '\Nebula\Widgets\Index',
            'action' => 'render',
            // 映射参数键及默认值
            'params' => [],
        ],
        'user' => [
            'widget' => '\Nebula\Widgets\User',
            'params' => [
                'action' => '',
            ],
        ],

        'options' => [
            'widget' => '\Nebula\Widgets\Options',
            'params' => [
                'action' => '',
            ],
        ]
    ];

    /**
     * 路由分发
     *
     * @return void
     */
    public static function dispatch()
    {
        $pathInfo = Request::getInstance()->pathinfo;
        $routeFragment = explode('/', $pathInfo);

        $actionOption = self::$routerWidgetMap[!empty($routeFragment[0]) ? array_shift($routeFragment) : 'index'] ?? null;
        if (null !== $actionOption) {
            // 参数键
            $paramkeys = array_keys($actionOption['params']);
            // 默认值
            $defaultValues = array_values($actionOption['params']);
            // 合并值
            $mergedValues = [];

            foreach ($defaultValues as $index => $value) {
                $mergedValues[$index] = $routeFragment[$index] ?? $value;
            }

            // 行动方法
            $action =  $actionOption['action'] ?? 'action';
            // 执行组件
            Widget::factory(
                $actionOption['widget'],
                array_combine($paramkeys, $mergedValues)
            )->$action();
        } else {
            Response::getInstance()->render('404');
        }
    }
}
