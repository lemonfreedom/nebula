<?php

namespace Content\Plugins\HelloWorld;

use Nebula\Plugin;

/**
 * name: HelloWorld
 * url: https://www.nebulaio.com/
 * description: 这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件这是一个测试插件
 * version: 1.0
 * author: Noah Zhang
 * author_url: http://www.nebulaio.com/
 */
class Main
{
    /**
     * 启用插件
     *
     * @return void
     */
    public static function activate()
    {
        Plugin::factory('admin/copyright.php')->begin = __CLASS__ . '::render';
    }

    /**
     * 停用插件
     *
     * @return void
     */
    public static function deactivate()
    {
    }

    /**
     * 插件配置
     *
     * @param $renderer 渲染器
     * @return void
     */
    public static function config($renderer)
    {
        $renderer->setValue('message', 'HelloWorld！');
        $renderer->setTemplate(function ($data) {
            include __DIR__ . '/config.php';
        });
    }

    /**
     * 插件自定义方法
     *
     * @param $data 数据
     */
    public static function render($data)
    {
        include __DIR__ . '/views/copyright.php';
    }
}