<?php require __DIR__ . '/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $action = $request->get('action'); ?>
<div class="container">
    <div class="nebula-tabs">
        <div class="scroll">
            <div class="tab<?= $action !== 'smtp' ? ' active' : '' ?>">
                <a href="/admin/options.php">基本设置</a>
            </div>
            <div class="tab<?= $action === 'smtp' ? ' active' : '' ?>">
                <a href="/admin/options.php?action=smtp">SMTP 设置</a>
            </div>
        </div>
    </div>
    <?php if ($action === 'smtp') : ?>
        <!-- SMTP 设置 -->
        <form id="smtpOptionForm" class="nebula-form" action="/option/update-smtp" method="POST">
            <div class="form-item">
                <label class="form-label" for="host">主机名</label>
                <input class="nebula-input" id="host" name="host" value="<?= $option->smtp['host'] ?>"></input>
            </div>
            <div class="form-item">
                <label class="form-label" for="port">端口</label>
                <input class="nebula-input" id="port" name="port" value="<?= $option->smtp['port'] ?>"></input>
            </div>
            <div class="form-item">
                <label class="form-label" for="username">用户名</label>
                <input class="nebula-input" id="username" name="username" value="<?= $option->smtp['username'] ?>"></input>
            </div>
            <div class="form-item">
                <label class="form-label" for="password">密码</label>
                <input class="nebula-input" id="password" type="password" name="password" value="<?= $option->smtp['password'] ?>"></input>
            </div>
            <div class="form-item">
                <label class="form-label" for="name">发件人名称</label>
                <input class="nebula-input" id="name" name="name" value="<?= $option->smtp['name'] ?>"></input>
            </div>
            <div class="form-item">
                <label class="form-label" for="email">发件人邮箱</label>
                <input class="nebula-input" id="email" name="email" value="<?= $option->smtp['email'] ?>"></input>
            </div>
            <div class="form-tools">
                <div class="nebula-button-group">
                    <button id="sendTestMail" type="button" class="nebula-button">发送测试邮件</button>
                    <button class="nebula-button">保存设置</button>
                </div>
            </div>
        </form>
    <?php else : ?>
        <!-- 基本设置 -->
        <form class="nebula-form" action="/option/update-basic" method="POST">
            <div class="form-item">
                <label class="form-label" for="title">站点名称</label>
                <input class="nebula-input" id="title" name="title" value="<?= $option->title ?>"></input>
                <div class="form-sublabel">站点的名称将显示在网页的标题处</div>
            </div>
            <div class="form-item">
                <label class="form-label" for="description">站点描述</label>
                <input class="nebula-input" id="description" name="description" value="<?= $option->description ?>"></input>
                <div class="form-sublabel">站点描述将显示在网页代码的头部</div>
            </div>
            <div class="form-item">
                <label class="form-label" for="allowRegister">是否允许注册</label>
                <div class="nebula-radio-group">
                    <label class="nebula-radio">
                        <input type="radio" name="allowRegister" value="0" <?= $option->allowRegister === '0' ? 'checked' : '' ?>>
                        <div class="checkmark"></div>
                        <span>否</span>
                    </label>
                    <label class="nebula-radio">
                        <input type="radio" name="allowRegister" value="1" <?= $option->allowRegister === '1' ? 'checked' : '' ?>>
                        <div class="checkmark"></div>
                        <span>是</span>
                    </label>
                </div>
                <div class="form-sublabel">允许访问者注册到你的网站</div>
            </div>
            <div class="form-tools">
                <button class="nebula-button">保存设置</button>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
