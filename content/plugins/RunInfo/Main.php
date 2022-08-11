<?php

namespace Content\Plugins\RunInfo;

use Nebula\Helpers\MySQL;
use Nebula\Plugin;

/**
 * name: 运行信息
 * url: https://www.nbacms.com/
 * description: 在底部输出程序运行信息
 * version: 1.0
 * author: Noah Zhang
 * author_url: http://www.nbacms.com/
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
        Plugin::factory('admin/common.php')->begin = __CLASS__ . '::begin';
        Plugin::factory('admin/footer.php')->render = __CLASS__ . '::render';
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
        $renderer->setValue('showSQL', '0');
        $renderer->setTemplate(function ($data) {
            include __DIR__ . '/config.php';
        });
    }

    public static function begin()
    {
        define('START_TIME', microtime(true));
    }

    public static function render($data)
    {
        $time = round(microtime(true) - START_TIME, 4);
        $sqls = MySQL::getInstance()->sqls;
        include __DIR__ . '/views/time.php';
    }
}
