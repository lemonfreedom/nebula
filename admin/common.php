<?php
// 定义根路径
define('NEBULA_ROOT_PATH', dirname(__DIR__) . '/');

// 载入程序配置
if (!@include NEBULA_ROOT_PATH . '/config.php') {
    file_exists(NEBULA_ROOT_PATH . '/install.php') ? header('Location: /install.php') : print('Missing Config File');
    exit;
}

// 初始化
\Nebula\Common::init();

// 缓存组件
\Nebula\Widgets\Cache::factory()->to($cache);

// 配置组件
\Nebula\Widgets\Option::factory()->to($option);

// 请求对象
$request = \Nebula\Request::getInstance();

// 响应对象
$response = \Nebula\Response::getInstance();
