<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->hasLogin() && $response->redirect('/'); ?>
<?php $option->get('allowRegister') || $response->redirect('/'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<div class="nebula-account">
    <div class="board">
        <h1 class="title">Nebula</h1>
        <form class="nebula-form" action="/user/register" method="POST">
            <div class="form-item">
                <input class="nebula-input" type="text" name="username" placeholder="用户名" value="<?= $cache->get('registerUsername', '') ?>">
            </div>
            <?php if (\Nebula\Plugin::factory('admin/register.php')->is('emailFormItem')) : ?>
                <?php \Nebula\Plugin::factory('admin/register.php')->emailFormItem(); ?>
            <?php else : ?>
                <div class="form-item">
                    <input class="nebula-input" type="email" name="email" placeholder="邮箱" value="<?= $cache->get('registerEmail', '') ?>">
                </div>
            <?php endif; ?>
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
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
