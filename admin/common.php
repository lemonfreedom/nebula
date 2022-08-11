<?php
// 定义根路径
define('NEBULA_ROOT_PATH', dirname(__DIR__) . '/');

// 加载公共文件
include_once NEBULA_ROOT_PATH . 'includes/Common.php';

// 载入程序配置
if (!@include_once NEBULA_ROOT_PATH . 'config.php') {
    file_exists(NEBULA_ROOT_PATH . 'install.php') ? header('Location: /install.php') : exit('Missing Config File');
}

// 初始化
\Nebula\Common::init();

// 注册一个开始插件
\Nebula\Plugin::factory('admin/common.php')->begin();

// 请求对象
$request = \Nebula\Request::getInstance();

// 响应对象
$response = \Nebula\Response::getInstance();

// 缓存组件
$cache = \Nebula\Widgets\Cache::factory();

// 配置组件
$option = \Nebula\Widgets\Option::factory();

// 用户组件
$user = \Nebula\Widgets\User::factory();
