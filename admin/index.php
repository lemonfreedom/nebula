<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<div class="container">
    <?= \Nebula\Helpers\Template::tabs(
        [
            ['name' => '通知', 'path' => "/admin/index.php", 'active' => null, 'has' => true],
        ],
        $action,
        \Nebula\Plugin::factory('admin/index.php')->tab(['action' => $action])
    ) ?>
    <?php if (null === $action) : ?>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/modules/copyright.php'; ?>
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
