<?php
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

// 路由分发
\Nebula\Router::dispatch();
