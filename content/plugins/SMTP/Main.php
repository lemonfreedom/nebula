<?php

namespace Content\Plugins\HelloWorld;

use Nebula\Plugin;

/**
 * name: 你好世界
 * url: https://www.nebulaio.com/
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
        Plugin::factory('admin/options.php')->tab = __CLASS__ . '::tab';
        Plugin::factory('admin/options.php')->tabContent = __CLASS__ . '::tabContent';
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
    }

    /**
     * tab 页面
     *
     * @param $data 数据
     */
    public static function tab($data)
    {
        include __DIR__ . '/views/tab.php';
    }

    /**
     * tab 内容页面
     *
     * @param $data 数据
     */
    public static function tabContent($data)
    {
        include __DIR__ . '/views/tabContent.php';
    }
}
