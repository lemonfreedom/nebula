<?php $this->render('modules/header'); ?>
<?php $user = \Nebula\Widgets\User::factory(); ?>
<div class="container">
    <div>文章详情页</div>
    <a href="/">返回首页</a>
    <?php if ($user->hasLogin()) : ?>
        欢迎：<?= $user->get('username') ?>
        <a href="/user/logout">退出登录</a>
        <a href="/admin">后台管理</a>
    <?php else : ?>
        <a href="/admin/login.php">登录</a>
    <?php endif; ?>
    <div>主题参数：<?php print_r($data['theme_config']) ?></div>
    <h1><?= $data['title'] ?></h1>
    <p>
        <span><?= $data['term_name'] ?></span>
        <span><?= $data['create_time'] ?></span>
    </p>
    <div class="markdown">
        <?= $data['parse_content'] ?>
    </div>
</div>
<?php $this->render('modules/footer'); ?>
