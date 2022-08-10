<?php if ('smtp' === $action) : ?>
    <!-- SMTP 设置 -->
    <?php $smtp = $option->get('smtp'); ?>
    <form id="smtpOptionForm" class="nebula-form" action="/option/update/smtp" method="POST">
        <div class="form-item">
            <label class="form-label" for="host">主机名</label>
            <input class="nebula-input" id="host" name="host" value="<?= $smtp['host'] ?>"></input>
        </div>
        <div class="form-item">
            <label class="form-label" for="port">端口</label>
            <input class="nebula-input" id="port" name="port" value="<?= $smtp['port'] ?>"></input>
        </div>
        <div class="form-item">
            <label class="form-label" for="username">用户名</label>
            <input class="nebula-input" id="username" name="username" value="<?= $smtp['username'] ?>"></input>
        </div>
        <div class="form-item">
            <label class="form-label" for="password">密码</label>
            <input class="nebula-input" id="password" type="password" name="password" value="<?= $smtp['password'] ?>"></input>
        </div>
        <div class="form-item">
            <label class="form-label" for="name">发件人名称</label>
            <input class="nebula-input" id="name" name="name" value="<?= $smtp['name'] ?>"></input>
        </div>
        <div class="form-item">
            <label class="form-label" for="email">发件人邮箱</label>
            <input class="nebula-input" id="email" name="email" value="<?= $smtp['email'] ?>"></input>
        </div>
        <div class="form-tools">
            <div class="nebula-button-group">
                <button id="sendTestMail" type="button" class="nebula-button">发送测试邮件</button>
                <button class="nebula-button">保存设置</button>
            </div>
        </div>
    </form>
<?php endif; ?>
