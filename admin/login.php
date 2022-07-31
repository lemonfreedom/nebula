<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() && $response->redirect('/'); ?>
<?php require __DIR__ . '/header.php'; ?>
<div class="nebula-account">
    <div class="board">
        <h1 class="title">Nebula</h1>
        <form class="nebula-form" action="/user/login" method="POST">
            <div class="form-item">
                <input class="nebula-input" type="text" name="account" placeholder="用户名" value="<?= \Nebula\Helpers\Cookie::get('account', '') ?>">
            </div>
            <div class="form-item">
                <input class="nebula-input" type="password" name="password" placeholder="密码">
            </div>
            <button type="submit" class="nebula-button block">登录</button>
        </form>
        <div class="tools">
            <a href="/">返回首页</a>
            <?php if ($options->boolParse('allowRegister')) : ?>
                <a href="/admin/register.php">立即注册</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
