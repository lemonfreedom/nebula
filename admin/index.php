<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $options->response->redirect('/admin/login.php'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<div class="container">首页</div>
<?php require __DIR__ . '/footer.php'; ?>
