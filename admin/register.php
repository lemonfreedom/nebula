<?php require __DIR__ . '/common.php'; ?>
<?php $options->boolParse('allowRegister') || $response->redirect('/'); ?>
<?php $user->hasLogin() && $response->redirect('/'); ?>
<?php require __DIR__ . '/header.php'; ?>
<div class="nebula-account">
    <div class="board">
        <h1 class="title">Nebula</h1>
        <form class="nebula-form" action="/user/register" method="POST">
            <div class="form-item">
                <input class="nebula-input" type="text" name="username" placeholder="用户名" value="<?= \Nebula\Helpers\Cookie::get('username', '') ?>">
            </div>
            <div class="form-item">
                <div class="group">
                    <input class="nebula-input" type="email" name="email" placeholder="邮箱" value="<?= \Nebula\Helpers\Cookie::get('email', '') ?>">
                    <button id="sendCaptcha" type="button" class="nebula-button">发送</button>
                </div>
            </div>
            <div class="form-item">
                <input class="nebula-input" type="text" name="code" placeholder="验证码" value="<?= \Nebula\Helpers\Cookie::get('code', '') ?>">
            </div>
            <div class="form-item">
                <input class="nebula-input" type="password" name="password" placeholder="密码">
            </div>
            <div class="form-item">
                <input class="nebula-input" type="password" name="confirmPassword" placeholder="确认密码">
            </div>
            <button type="submit" class="nebula-button block">注册</button>
        </form>
        <div class="tools">
            <a href="/admin/login.php">返回登陆</a>
        </div>
    </div>
</div>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
