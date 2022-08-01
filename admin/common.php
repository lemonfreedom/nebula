<?php
// 载入程序配置
if (!@include dirname(__DIR__) . '/config.php') {
    file_exists(dirname(__DIR__) . '/install.php') ? header('Location: /install.php') : print('Missing Config File');
    exit;
}

// 配置组件
\Nebula\Widgets\Option::alloc()->to($options);

// 用户组件
\Nebula\Widgets\User::alloc()->to($user);

// 请求对象
$request = \Nebula\Request::getInstance();

// 响应对象
$response = \Nebula\Response::getInstance();
