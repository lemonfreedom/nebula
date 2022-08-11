<?php

/**
 * This file is part of Nebula.
 *
 * (c) 2022 nbacms <nbacms@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

// 定义根路径
define('NEBULA_ROOT_PATH', __DIR__ . '/');

// 加载公共文件
include_once NEBULA_ROOT_PATH . 'includes/Common.php';

// 载入程序配置
if (!@include_once NEBULA_ROOT_PATH . 'config.php') {
    file_exists(NEBULA_ROOT_PATH . 'install.php') ? header('Location: /install.php') : exit('Missing Config File');
}

// 初始化
\Nebula\Common::init();

// 注册一个开始插件
\Nebula\Plugin::factory('index.php')->begin();

// 路由分发
\Nebula\Router::dispatch();

// 注册一个结束插件
\Nebula\Plugin::factory('index.php')->end();
