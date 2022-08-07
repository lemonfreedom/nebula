<?php \Nebula\Widgets\User::factory()->to($user); ?>
<?php $this->render('header'); ?>
<div class="container">
    <div>这是主题 nebula</div>
    <?php if ($user->hasLogin()) : ?>
        欢迎：<?= $user->getUserInfo('username') ?>
        <a href="/user/logout">退出登录</a>
        <a href="/admin">后台管理</a>
    <?php else : ?>
        <a href="/admin/login.php">登录</a>
    <?php endif; ?>
</div>
<div>主题参数：<?php print_r($data['theme_config']) ?></div>
<?php $this->render('footer'); ?>
