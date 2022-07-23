<?php
// 载入程序配置
if (!@include dirname(__DIR__) . '/config.php') {
    file_exists(dirname(__DIR__) . '/install.php') ? header('Location: /install.php') : print('Missing Config File');
    exit;
}

// 配置组件
\Nebula\Widgets\Options::alloc()->to($options);

// 用户组件
\Nebula\Widgets\User::alloc()->to($user);
