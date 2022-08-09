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

$result = $mysql->select('users', ['password'])->where([
    'OR' => [
        'uid' => 1,
        'email' => 1,
    ]
])->execute();

// 创建配置表
// $mysql->create('options', [
//     'name' => ['VARCHAR(30)', 'NOT NULL', 'PRIMARY KEY'],
//     'value' => ['LONGTEXT', 'NOT NULL'],
// ]);

// // 插入配置数据
// $result = $mysql
//     ->update("options", ['value' => '222'])
//     ->where(['name' => 'theme'])
//     ->execute();

// $result = $mysql->delete('users', ['username'])
//     ->where([
//         'usern1ame' => 'admi1n'
//     ])->execute();
// echo "<pre>";

print_r($result);
