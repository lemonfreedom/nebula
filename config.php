<?php
// 调试模式
define('NEBULA_DEBUG', true);

// 数据库配置
define('NEBULA_DB_CONFIG', [
    // 必填
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => 'nebula',
    'username' => 'root',
    'password' => 'root',

    // 可选
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_general_ci',
    'port' => 3306,
    'prefix' => 'nebula_',
    'logging' => false,
    'error' => PDO::ERRMODE_SILENT,
    'option' => [PDO::ATTR_CASE => PDO::CASE_NATURAL],
    'command' => ['SET SQL_MODE=ANSI_QUOTES'],
]);
