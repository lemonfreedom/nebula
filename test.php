<?php

use Nebula\Helpers\MySQL;

define('NEBULA_ROOT_PATH', __DIR__ . '/');

include __DIR__ . '/includes/Common.php';

$mysql = MySQL::getInstance();

$mysql->init([
    'dbname' => 'nebula',
    'host' => 'localhost',
    'port' => '3306',
    'username' => 'root',
    'password' => 'root',
    'prefix' => 'nebula_',
]);

// 创建配置表
$mysql->create('options', [
    'name' => ['VARCHAR(30)', 'NOT NULL', 'PRIMARY KEY'],
    'value' => ['LONGTEXT', 'NOT NULL'],
]);

// 插入配置数据
$mysql->insert("options", ['name' => 'description', 'value' => '又一个博客网站诞生了']);
