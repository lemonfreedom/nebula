<?php

use Nebula\Helpers\MySQL;
use Nebula\Helpers\Validate;

define('NEBULA_ROOT_PATH', __DIR__ . '/');

include __DIR__ . '/includes/Common.php';

// $mysql = MySQL::getInstance();

// $mysql->init([
//     'dbname' => 'nebula',
//     'host' => 'localhost',
//     'port' => '3306',
//     'username' => 'root',
//     'password' => 'root',
//     'prefix' => 'nebula_',
// ]);

// $result = $mysql->select('users', ['password'])->where([
//     'OR' => [
//         'uid' => 1,
//         'email' => 1,
//     ]
// ])->execute();

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
// $res = $mysql->has('users')->execute();
class Test
{
    public  function check($value, $callable)
    {
        echo $value;
        $callable('你好');
    }

    public  function a()
    {
        $data = [
            'username' => '1',
            'email' => '1@q.com',
            'password' => '123456789',
            'password1' => '1234567819',
        ];
        $validate = new Validate($data, [
            'username' => [
                ['type' => 'required', 'message' => '用户名不能为空'],
            ],
            'email' => [
                ['type' => 'required', 'message' => '邮箱不能为空'],
                ['type' => 'email', 'message' => '邮箱格式不正确'],
            ],
            'password' => [
            ],
            'password1' => [
                [
                    'type' => 'custom',
                    'validator' => function ($rule, $value, $callback) use ($data) {
                        if ($data['password'] !== $value) {
                            $callback('两次输入密码不一致');
                        }
                    },
                ]
            ],
        ]);
        if (!$validate->run()) {
            print_r($validate->result[0]['message']);
        } else {
            echo '成功';
        }
    }
}

(new Test())->a();
