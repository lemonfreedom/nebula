<?php require __DIR__ . '/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php $plugin = \Nebula\Widgets\Plugin::factory(['pluginName' => $request->get('name')]); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<div class="container">
    <div class="nebula-title">
        <div>
            <span>插件配置「<?= $plugin->get('name') ?>」</span>
            <a href="/admin/plugins.php">返回</a>
        </div>
    </div>
    <?php \Nebula\Widgets\Plugin::factory()->config() ?>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
