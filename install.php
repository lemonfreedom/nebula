<?php
define('NEBULA_ROOT_PATH', __DIR__ . '/');

include NEBULA_ROOT_PATH . 'includes/Common.php';

// 步骤一
function step()
{
    $errorMessage = null;

    if (version_compare(PHP_VERSION, '7.3.0') === -1) {
        $errorMessage = 'PHP 版本最低为 7.3';
    }

    echo <<<EOT
<div class="nebula-title">欢迎使用 Nebula</div>
<div class="nebula-wecome">
<div class="title">安装说明</div>
<p>本安装程序将自动检测服务器环境是否符合最低配置需求。如果服务器环境符合要求，将在下方出现「<em class="mark">现在就开始</em>」按钮，点击此按钮即可开始下一步。</p>
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
<div class="error">很遗憾，你服务器不满足最低要求，错误信息：<strong><em>{$errorMessage}</em></strong></div>
EOT;
    }
}

// 步骤二
function step1()
{
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
        <label class="form-label" for="username">数据库用户名</label>
        <input class="nebula-input" id="username" name="username">
    </div>
    <div class="form-item">
        <label class="form-label" for="password">数据库密码</label>
        <input class="nebula-input" id="password" name="password">
    </div>
    <div class="form-item">
        <label class="form-label" for="prefix">数据库表前缀</label>
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
$db = null;
// 步骤三
function step2()
{
    $data = \Nebula\Request::getInstance()->post();
    try {
        // 连接数据库
        new \Nebula\Helpers\Medoo([
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
    } catch (\Throwable $th) {
        \Nebula\Widgets\Notice::alloc()->set('数据库连接失败：' . $th->getMessage(), 'warning');
        \Nebula\Response::getInstance()->redirect('install.php?step=1');
    }
    $configString = <<<EOT
<?php
// 定义根路径
define('NEBULA_ROOT_PATH', __DIR__ . '/');

// 调试模式
define('NEBULA_DEBUG', true);

// 数据库配置
define('NEBULA_DB_CONFIG', [
    // 必填
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => 'nebula',
    'username' => 'root',
    'password' => 'wo123456',

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

// 加载公共文件
require NEBULA_ROOT_PATH . 'includes/Common.php';

// 初始化
\Nebula\Common::init();
EOT;
    // 写入配置文件
    file_put_contents(NEBULA_ROOT_PATH . 'config.php', $configString);

    echo <<<EOT
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
EOT;
}

// 步骤四
function step3()
{
    echo <<<EOT
<div class="nebula-title">安装成功</div>
<div class="nebula-wecome">
    <p>您的用户名是：<span class="mark">admin</span></p>
    <p>您的密码是：<span class="mark">admin</span></p>
    <ul>
        <li><a href="/admin">点击这里访问您的控制面板</a></li>
        <li><a href="/">点击这里查看您的首页</a></li>
    </ul>
    <p>代码如诗，尽情享受 Nebula 带给你的无穷乐趣！</p>
</div>
EOT;
}

function error404()
{
    echo <<<EOT
<div class="nebula-title">404</div>
EOT;
}

function render()
{
    $funcName = 'step' . \Nebula\Request::getInstance()->get('step', '');

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
    <script src="/admin/js/js.cookie.min.js"></script>
    <script src="/admin/js/nebula.js"></script>
</body>

</html>
