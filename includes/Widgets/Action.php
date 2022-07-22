<?php

namespace Nebula\Widgets;

use Nebula\Widget;

class Action extends Widget
{
    /**
     * 路由组件映射
     *
     * @var array
     */
    private $routerWidgetMap = [
        'index' => [
            // 映射组件
            'widget' => '\Nebula\Widgets\Index',
            // 映射参数及默认值
            'params' => [
                'val1' => 'def1',
                'val2' => 'def2'
            ],
        ],
        'login' => [
            'widget' => '\Nebula\Widgets\User',
            'params' => [
                'val1' => 'def1',
                'val2' => 'def2'
            ],
        ],
    ];

    /**
     * 执行方法
     *
     * @var void
     */
    public function execute()
    {
        $action = isset($this->params[0]) ? array_shift($this->params) : 'index';

        if (isset($this->routerWidgetMap[$action])) {
            // 参数键
            $paramkeys = array_keys($this->routerWidgetMap[$action]['params']);
            // 默认值
            $defaultValues = array_values($this->routerWidgetMap[$action]['params']);
            // 合并值
            $mergedValues = [];

            foreach ($defaultValues as $index => $value) {
                $mergedValues[$index] = $this->params[$index] ?? $value;
            }

            self::factory($this->routerWidgetMap[$action]['widget'], array_combine($paramkeys, $mergedValues))->action();
        } else {
            $this->response->render404();
        }
    }
}
