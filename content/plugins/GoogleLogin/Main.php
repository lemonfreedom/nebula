<?php

namespace Content\Plugins\GoogleLogin;

use Nebula\Plugin;

/**
 * name: GoogleLogin
 * url: https://www.nbacms.com/
 * version: 1.0
 * author: Noah Zhang
 * author_url: http://www.nbacms.com/
 */
// 762030044736-giion0fd9jek83dj6p972k25oubl6vb1.apps.googleusercontent.com
class Main
{
    /**
     * 启用插件
     *
     * @return void
     */
    public static function activate()
    {
        Plugin::factory('admin/login.php')->btn = __CLASS__ . '::render';
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
        include __DIR__ . '/views/login-component.php';
    }
}
