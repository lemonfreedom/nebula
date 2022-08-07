<?php
// 定义根路径
define('NEBULA_ROOT_PATH', __DIR__ . '/');

// 载入程序配置
if (!@include NEBULA_ROOT_PATH . 'config.php') {
    file_exists(NEBULA_ROOT_PATH . 'install.php') ? header('Location: /install.php') : exit('Missing Config File');
}

// 加载公共文件
require NEBULA_ROOT_PATH . 'includes/Common.php';

// 初始化
\Nebula\Common::init();

// 注册一个开始插件
\Nebula\Plugin::factory('index.php')->begin();

// 路由分发
\Nebula\Router::dispatch();

// 注册一个结束插件
\Nebula\Plugin::factory('index.php')->end();


