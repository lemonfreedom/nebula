<?php require __DIR__ . '/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<div class="submenu">
    <div class="container">
        <ul class="menu">
            <li><a href="/admin/option-basic.php">基本设置</a></li>
            <li><a href="/admin/option-theme.php">主题</a></li>
            <li><a href="/admin/option-plugin.php">插件</a></li>
            <li class="active"><a href="/admin/option-smtp.php">SMTP 设置</a></li>
        </ul>
    </div>
</div>
<div class="container">
    <form class="nebula-form" action="/option/update-smtp" method="post">
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
        <div class="form-tools">
            <div class="nebula-button-group">
                <button id="sendTestMail" type="button" class="nebula-button">发送测试邮件</button>
                <button class="nebula-button">保存设置</button>
            </div>
        </div>
    </form>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>