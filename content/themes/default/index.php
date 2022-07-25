<?php \Nebula\Widgets\User::alloc()->to($user); ?>
<?php $this->render('header'); ?>
<div class="container">
    <?php if ($user->hasLogin()) : ?>
        欢迎：<?= $user->get('username') ?>
        <a href="/user/logout">退出登录</a>
    <?php else : ?>
        <a href="/admin/login.php">登录</a>
    <?php endif; ?>

</div>
<?php $this->render('footer'); ?>
