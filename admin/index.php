<?php include __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/navbar.php'; ?>
<div class="container">
    <h2 class="nebula-title">通知</h2>
</div>
<?php include __DIR__ . '/copyright.php'; ?>
<?php include __DIR__ . '/common-js.php'; ?>
<?php include __DIR__ . '/footer.php'; ?>
