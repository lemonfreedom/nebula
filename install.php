<?php

use Nebula\Helpers\Medoo;
use Nebula\Helpers\Validate;
use Nebula\Request;
use Nebula\Response;
use Nebula\Widgets\Notice;
use Nebula\Widgets\Option;
use Nebula\Widgets\User;

define('NEBULA_ROOT_PATH', __DIR__ . '/');

include NEBULA_ROOT_PATH . 'includes/Common.php';

// SMTP 配置
function smtp_config()
{
    return [
        'host' => '',
        'username' => '',
        'password' => '',
        'port' => '',
        'name' => '',
        'email' => ''
    ];
}

// 检查是否初始化
function has_been_init()
{
    if (!file_exists(NEBULA_ROOT_PATH . 'config.php')) {
        return false;
    }

    include_once NEBULA_ROOT_PATH . 'config.php';
    return true;
}

// 检查管理员是否存在
function administrator_exists()
{
    return \Nebula\Widgets\Option::alloc()->db->count('users') > 0;
}

// 安装已完成跳出安装程序
if (has_been_init() && administrator_exists()) {
    Response::getInstance()->redirect('/');
}

// 欢迎界面
function step()
{
    $errorMessage = null;

    if (version_compare(PHP_VERSION, '7.3.0') === -1) {
        $errorMessage = '「PHP 版本最低为 7.3」';
    }

    echo <<<EOT
<div class="nebula-title">欢迎使用 Nebula</div>
<div class="nebula-wecome">
<div class="title">安装说明</div>
<p>本安装程序将自动检测服务器环境是否符合安装需求。如果服务器环境符合要求，将在下方出现「<em class="mark">现在就开始</em>」按钮，点击此按钮即可开始下一步。</p>
<div class="title">许可协议</div>
<ul>
    <li>Nebula 基于 <em class="mark">GNU 通用公共许可证 v3.0</em>，我们提供许可作品和修改的完整源代码，其中包括在同一许可下使用许可作品的大型作品。但必须保留版权和许可声明。</li>
</ul>
EOT;
    if (null === $errorMessage) {
        echo <<<EOT
<a href="/install.php?step=1" class="nebula-button">
    <span>现在就开始</span>
    <i class="bi bi-chevron-double-right"></i>
</a>
</div>
EOT;
    } else {
        echo <<<EOT
<div class="title error">无法安装：</div>
<ul class="error">
    {$errorMessage}
</ul>
EOT;
    }
}

// 步骤一
function step1()
{
    // 如果已经初始化跳过此步
    if (has_been_init()) {
        Response::getInstance()->redirect('/install.php?step=2');
    }

    echo <<<EOT
<div class="nebula-title">数据库配置</div>
<form class="nebula-form" action="/install.php?step=2" method="post">
    <div class="form-item">
        <label class="form-label" for="host">数据库地址</label>
        <input class="nebula-input" id="host" name="host" value="localhost">
    </div>
    <div class="form-item">
        <label class="form-label" for="port">数据库端口</label>
        <input class="nebula-input" id="port" name="port" value="3306">
        <label class="form-sublabel">MySQL 端口默认 3306</label>
    </div>
    <div class="form-item">
        <label class="form-label" for="database">数据库名</label>
        <input class="nebula-input" id="database" name="database" value="nebula">
    </div>
    <div class="form-item">
        <label class="form-label" for="username">用户名</label>
        <input class="nebula-input" id="username" name="username">
    </div>
    <div class="form-item">
        <label class="form-label" for="password">密码</label>
        <input class="nebula-input" type="password" id="password" name="password">
    </div>
    <div class="form-item">
        <label class="form-label" for="prefix">表前缀</label>
        <input class="nebula-input" id="prefix" name="prefix" value="nebula_">
        <label class="form-sublabel">同数据库多程序请设置前缀</label>
    </div>
    <div class="form-item">
        <label class="form-label" for="charset">字符集</label>
        <input class="nebula-input" id="charset" name="charset" value="utf8mb4">
        <label class="form-sublabel">如不了解，默认即可</label>
    </div>
    <div class="form-item">
        <label class="form-label" for="collation">整理类型</label>
        <input class="nebula-input" id="collation" name="collation" value="utf8mb4_general_ci">
        <label class="form-sublabel">如不了解，默认即可</label>
    </div>
    <button class="nebula-button" type="submit">
        <span>开始安装</span>
        <i class="bi bi-chevron-double-right"></i>
    </button>
</form>
EOT;
}

// 步骤二
function step2()
{
    // 未初始化，新增配置文件
    if (!has_been_init()) {
        $data = Request::getInstance()->post();

        $validate = new Validate($data, [
            'host' => [
                ['type' => 'required', 'message' => '数据库地址不能为空'],
            ],
            'port' => [
                ['type' => 'required', 'message' => '数据库端口不能为空'],
            ],
            'database' => [
                ['type' => 'required', 'message' => '数据库名不能为空'],
            ],
            'username' => [
                ['type' => 'required', 'message' => '用户名不能为空'],
            ],
            'password' => [
                ['type' => 'required', 'message' => '密码不能为空'],
            ],
            'charset' => [
                ['type' => 'required', 'message' => '字符集不能为空'],
            ],
            'collation' => [
                ['type' => 'required', 'message' => '整理类型不能为空'],
            ],
        ]);
        // 表单验证
        if (!$validate->run()) {
            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            Response::getInstance()->redirect('/install.php?step=1');
        }

        try {
            // 连接数据库
            $db = new Medoo([
                // 必填
                'type' => 'mysql',
                'host' => $data['host'],
                'database' => $data['database'],
                'username' => $data['username'],
                'password' => $data['password'],

                // 可选
                'charset' => $data['charset'],
                'collation' => $data['collation'],
                'port' => $data['port'],
                'prefix' => $data['prefix'],
                'logging' => false,
                'error' => PDO::ERRMODE_SILENT,
                'option' => [PDO::ATTR_CASE => PDO::CASE_NATURAL],
                'command' => ['SET SQL_MODE=ANSI_QUOTES'],
            ]);
        } catch (\PDOException $e) {
            Notice::alloc()->set('数据库连接失败：' . $e->getMessage(), 'warning');
            Response::getInstance()->redirect('/install.php?step=1');
        }
        $configString = <<<EOT
<?php
// 调试模式
define('NEBULA_DEBUG', true);

// 数据库配置
define('NEBULA_DB_CONFIG', [
    // 必填
    'type' => 'mysql',
    'host' => '{$data['host']}',
    'database' => '{$data['database']}',
    'username' => '{$data['username']}',
    'password' => '{$data['password']}',

    // 可选
    'charset' => '{$data['charset']}',
    'collation' => '{$data['collation']}',
    'port' => {$data['port']},
    'prefix' => '{$data['prefix']}',
    'logging' => false,
    'error' => PDO::ERRMODE_SILENT,
    'option' => [PDO::ATTR_CASE => PDO::CASE_NATURAL],
    'command' => ['SET SQL_MODE=ANSI_QUOTES'],
]);\n
EOT;

        try {
            $db->action(function ($db) {
                // 删除表
                $db->drop('users');
                $db->drop('options');
                $db->drop('terms');
                $db->drop('posts');
                $db->drop('caches');

                // 创建用户表
                $db->create('users', [
                    'uid' => ['int', 'UNSIGNED', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
                    'role' => ['TINYINT', 'UNSIGNED', 'NOT NULL'],
                    'nickname' => ['VARCHAR(60)', 'NOT NULL'],
                    'username' => ['VARCHAR(60)', 'NOT NULL', 'UNIQUE'],
                    'password' => ['VARCHAR(64)', 'NOT NULL', 'UNIQUE'],
                    'email' => ['VARCHAR(100)', 'NOT NULL', 'UNIQUE'],
                    'token' => ['VARCHAR(32)', 'NOT NULL'],
                ]);

                // 创建配置表
                $db->create('options', [
                    'name' => ['VARCHAR(30)', 'NOT NULL', 'PRIMARY KEY'],
                    'value' => ['LONGTEXT', 'NOT NULL'],
                ]);
                // 插入配置数据
                $db->insert("options", [
                    ['name' => 'title', 'value' => ''],
                    ['name' => 'description', 'value' => '又一个博客网站诞生了'],
                    ['name' => 'allowRegister', 'value' => '0'],
                    ['name' => 'smtp', 'value' => serialize(smtp_config())],
                    ['name' => 'plugins', 'value' => 'a:0:{}'],
                    ['name' => 'theme', 'value' => 'a:2:{s:4:"name";s:7:"default";s:6:"config";a:0:{}}'],
                ]);

                // 创建分类表
                $db->create('terms', [
                    'tid' => ['TINYINT', 'UNSIGNED', 'NOT NULL', 'PRIMARY KEY'],
                    'name' => ['VARCHAR(30)', 'NOT NULL'],
                ]);

                // 创建文章表
                $db->create('posts', [
                    'pid' => ['int', 'UNSIGNED', 'NOT NULL', 'AUTO_INCREMENT', 'PRIMARY KEY'],
                    'tid' => ['TINYINT', 'UNSIGNED', 'NOT NULL'],
                    'title' => ['VARCHAR(60)', 'NOT NULL'],
                    'content' => ['LONGTEXT', 'NOT NULL'],
                ]);

                // 创建缓存表
                $db->create('caches', [
                    'name' => ['VARCHAR(200)', 'NOT NULL', 'PRIMARY KEY'],
                    'value' => ['LONGTEXT', 'NOT NULL'],
                    'expires' => ['int', 'UNSIGNED', 'NOT NULL'],
                ]);
            });
        } catch (\PDOException $e) {
            Notice::alloc()->set('数据库初始化失败：' . $e->getMessage(), 'warning');
            Response::getInstance()->redirect('/install.php?step=1');
        }

        // 写入配置文件
        file_put_contents(NEBULA_ROOT_PATH . 'config.php', $configString);
    }

    // 未初始化跳到步骤一
    if (!has_been_init()) {
        Response::getInstance()->redirect('/install.php?step=1');
    }

    // 存在管理员退出安装程序
    if (administrator_exists()) {
        Response::getInstance()->redirect('/');
    }

    echo <<<EOT
<div class="nebula-title">站点设置</div>
<form class="nebula-form" action="/install.php?step=3" method="post">
    <div class="form-item">
        <label class="form-label" for="title">站点名称</label>
        <input class="nebula-input" id="title" name="title" value="Nebula">
    </div>
    <div class="form-item">
        <label class="form-label" for="username">用户名</label>
        <input class="nebula-input" id="username" name="username">
        <label class="form-sublabel">请填写您的用户名</label>
    </div>
    <div class="form-item">
        <label class="form-label" for="password">密码</label>
        <input class="nebula-input" type="password" id="password" name="password">
        <label class="form-sublabel">请填写您的登录密码</label>
    </div>
    <div class="form-item">
        <label class="form-label" for="email">邮箱</label>
        <input class="nebula-input" id="email" name="email">
        <label class="form-sublabel">请填写一个您的常用邮箱</label>
    </div>
    <button class="nebula-button" type="submit">
        <span>继续安装</span>
        <i class="bi bi-chevron-double-right"></i>
    </button>
</form>
EOT;
}

// 步骤三
function step3()
{
    // 未初始化跳到步骤一
    if (!has_been_init()) {
        Response::getInstance()->redirect('/install.php?step=1');
    }

    // 存在管理员退出安装程序
    if (administrator_exists()) {
        Response::getInstance()->redirect('/');
    }

    $data = Request::getInstance()->post();

    $validate = new Validate($data, [
        'title' => [
            ['type' => 'required', 'message' => '站点名称不能为空'],
        ],
        'username' => [
            ['type' => 'required', 'message' => '用户名不能为空'],
        ],
        'password' => [
            ['type' => 'required', 'message' => '密码不能为空'],
        ],
        'email' => [
            ['type' => 'required', 'message' => '邮箱不能为空'],
            ['type' => 'email', 'message' => '邮箱格式不正确'],
        ],
    ]);
    // 表单验证
    if (!$validate->run()) {
        Notice::alloc()->set($validate->result[0]['message'], 'warning');
        Response::getInstance()->redirect('/install.php?step=2');
    }

    // 修改配置
    Option::alloc()->setOptions([
        'title' => $data['title'],
    ]);
    // 创建管理员
    User::alloc()->createUser($data['username'], $data['password'], $data['email'], '0');

    echo <<<EOT
<div class="nebula-title">安装成功</div>
<div class="nebula-wecome">
    <p>您的用户名是：<span class="mark">{$data['username']}</span></p>
    <p>您的密码是：<span class="mark">{$data['password']}</span></p>
    <ul>
        <li><a href="/admin">点击这里访问您的控制面板</a></li>
        <li><a href="/">点击这里查看您的首页</a></li>
    </ul>
    <p>代码如诗，尽情享受 Nebula 带给你的无穷乐趣！</p>
</div>
EOT;
}

function render()
{
    $funcName = 'step' . Request::getInstance()->get('step', '');

    if (function_exists($funcName)) {
        $funcName();
    } else {
        Response::getInstance()->redirect('/install.php');
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nebula 安装程序</title>
    <link rel="stylesheet" href="/admin/css/index.min.css">
    <link rel="stylesheet" href="/admin/css/bootstrap-icons.min.css">
</head>

<body>
    <div class="container install">
        <h1 class="logo">Nebula</h1>
        <?php render(); ?>
    </div>
    <script src="/admin/js/js.cookie.min.js"></script>
    <script src="/admin/js/nebula.js"></script>
</body>

</html>
