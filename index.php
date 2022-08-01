<?php
// 载入程序配置
if (!@include __DIR__ . '/config.php') {
    file_exists(__DIR__ . '/install.php') ? header('Location: /install.php') : exit('Missing Config File');
}

// 路由分发
\Nebula\Router::dispatch();
