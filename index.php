<?php
// 载入配置
if (!@include __DIR__ . '/config.php') {
    file_exists(__DIR__ . '/install.php') ? header('Location: /install.php') : exit('Missing Config File');
}

// 初始化
\Nebula\Common::init();

\Nebula\Router::dispatch();
