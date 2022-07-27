<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<div class="container">
    <h2 class="page-title">仪表盘</h2>
    <h2 class="page-subtitle">通知</h2>
    <h2 class="page-subtitle">最近发布</h2>
    <h2 class="page-subtitle">最近评论</h2>
    <h2 class="page-subtitle">个人统计</h2>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
