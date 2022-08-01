<?php
define('NEBULA_ROOT_PATH', __DIR__ . '/');

include NEBULA_ROOT_PATH . 'includes/Common.php';

// 步骤一
function step0()
{
    echo <<<HTML
<div class="nebula-title">欢迎使用 Nebula</div>
<div class="nebula-wecome">
    <div class="title">安装说明</div>
    <p>本安装程序将自动检测服务器环境是否符合最低配置需求。如果服务器环境符合要求，将在下方出现「<span class="strong">现在就开始</span>」的按钮，点击此按钮即可开始下一步。</p>
    <div class="title">许可协议</div>
    <ul>
        <li>Nebula 基于 <span class="strong">GNU 通用公共许可证 v3.0</span>，我们提供许可作品和修改的完整源代码，其中包括在同一许可下使用许可作品的大型作品。但必须保留版权和许可声明。</li>
    </ul>
    <a href="/install.php?step=1" class="nebula-button">
        <span>现在就开始</span>
        <i class="bi bi-chevron-double-right"></i>
    </a>
</div>
HTML;
}

// 步骤二
function step1()
{
    echo <<<HTML
<div class="nebula-title">数据库配置</div>
<form class="nebula-form" action="/install.php?step=2" method="post">
    <div class="form-item">
        <label class="form-label" for="host">数据库地址</label>
        <input class="nebula-input" id="host" name="host">
        <label class="form-sublabel"></label>
    </div>
    <div class="form-item">
        <label class="form-label" for="port">数据库端口</label>
        <input class="nebula-input" id="port" name="port">
        <label class="form-sublabel"></label>
    </div>
    <div class="form-item">
        <label class="form-label" for="database">数据库名</label>
        <input class="nebula-input" id="database" name="database">
        <label class="form-sublabel"></label>
    </div>
    <div class="form-item">
        <label class="form-label" for="username">数据库用户名</label>
        <input class="nebula-input" id="username" name="username">
        <label class="form-sublabel"></label>
    </div>
    <div class="form-item">
        <label class="form-label" for="password">数据库密码</label>
        <input class="nebula-input" id="password" name="password">
        <label class="form-sublabel"></label>
    </div>
    <div class="form-item">
        <label class="form-label" for="prefix">数据库表前缀</label>
        <input class="nebula-input" id="prefix" name="prefix">
        <label class="form-sublabel"></label>
    </div>
    <div class="form-item">
        <label class="form-label" for="charset">字符集</label>
        <input class="nebula-input" id="charset" name="charset" value="utf8mb4">
        <label class="form-sublabel"></label>
    </div>
    <div class="form-item">
        <label class="form-label" for="collation">整理类型</label>
        <input class="nebula-input" id="collation" name="collation" value="utf8mb4_general_ci">
        <label class="form-sublabel"></label>
    </div>
    <button class="nebula-button" type="submit">
        <span>开始安装</span>
        <i class="bi bi-chevron-double-right"></i>
    </button>
</form>
HTML;
}

// 步骤三
function step2()
{
    echo <<<HTML
<div class="nebula-title">站点设置</div>
<form class="nebula-form" action="/install.php?step=3" method="post">
    <div class="form-item">
        <label class="form-label" for="host">站点名称</label>
        <input class="nebula-input" id="host" name="host">
        <label class="form-sublabel"></label>
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
        <label class="form-label" for="email">邮箱地址</label>
        <input class="nebula-input" id="email" name="email">
        <label class="form-sublabel">请填写一个您的常用邮箱</label>
    </div>
    <button class="nebula-button" type="submit">
        <span>继续安装</span>
        <i class="bi bi-chevron-double-right"></i>
    </button>
</form>
HTML;
}

// 步骤四
function step3()
{
    echo <<<HTML
<div class="nebula-title">安装成功</div>
<div class="nebula-wecome">
    <p>您的用户名是：<strong class="strong">admin</strong></p>
    <p>您的密码是：<strong class="strong">admin</strong></p>
    <ul>
        <li><a href="/admin">点击这里访问您的控制面板</a></li>
        <li><a href="/">点击这里查看您的首页</a></li>
    </ul>
    <p>代码如诗，尽情享受 Nebula 带给你的无穷乐趣！</p>
</div>
HTML;
}

function error404()
{
    echo <<<HTML
<div class="nebula-title">404</div>
HTML;
}

function render()
{
    $funcName = ('step' . Nebula\Request::getInstance()->get('step', '0'));

    if (function_exists($funcName)) {
        $funcName();
    } else {
        error404();
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
</body>

</html>
