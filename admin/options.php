<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<div class="container">
    <h2 class="page-title">
        <span>设置</span>
    </h2>
    <form action="/options/update-basic" method="post">
        <div class="page-subtitle">
            <span>基本设置</span>
            <button class="nebula-button">保存设置</button>
        </div>
        <div class="form-item">
            <label class="form-label" for="title">站点名称</label>
            <input class="nebula-input" id="title" name="title" value="<?= $options->title ?>"></input>
            <div class="form-sublabel">站点的名称将显示在网页的标题处</div>
        </div>
        <div class="form-item">
            <label class="form-label" for="description">站点描述</label>
            <input class="nebula-input" id="description" name="description" value="<?= $options->description ?>"></input>
            <div class="form-sublabel">站点描述将显示在网页代码的头部</div>
        </div>
        <div class="form-item">
            <label class="form-label" for="allowRegister">是否允许注册</label>
            <label class="nebula-switch">
                <input type="checkbox" id="allowRegister" name="allowRegister" value="1" <?= $options->allowRegister === '1' ? 'checked' : '' ?>>
                <span class="slider"></span>
            </label>
            <div class="form-sublabel">允许访问者注册到你的网站</div>
        </div>
    </form>
    <form action="/options/update-smtp" method="post">
        <div class="page-subtitle">
            <span>SMTP 设置</span>
            <div class="actions">
                <button id="sendTestMail" type="button" class="nebula-button">发送测试邮件</button>
                <button class="nebula-button">保存设置</button>
            </div>
        </div>
        <div class="form-item">
            <label class="form-label" for="host">主机名</label>
            <input class="nebula-input" id="host" name="host" value="<?= $options->smtp['host'] ?>"></input>
        </div>
        <div class="form-item">
            <label class="form-label" for="port">端口</label>
            <input class="nebula-input" id="port" name="port" value="<?= $options->smtp['port'] ?>"></input>
        </div>
        <div class="form-item">
            <label class="form-label" for="name">名称</label>
            <input class="nebula-input" id="name" name="name" value="<?= $options->smtp['name'] ?>"></input>
            <div class="form-sublabel">系统发送邮箱名称</div>
        </div>
        <div class="form-item">
            <label class="form-label" for="username">用户名</label>
            <input class="nebula-input" id="username" name="username" value="<?= $options->smtp['username'] ?>"></input>
            <div class="form-sublabel">发件邮箱名</div>
        </div>
        <div class="form-item">
            <label class="form-label" for="password">密码</label>
            <input class="nebula-input" id="password" type="password" name="password" value="<?= $options->smtp['password'] ?>"></input>
            <div class="form-sublabel">发件邮箱密码</div>
        </div>
    </form>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
