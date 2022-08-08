<?php require __DIR__ . '/common.php'; ?>
<?php \Nebula\Widgets\Users\Method::factory()->inRole(['0']) || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<div class="container">
    <?php \Nebula\Widgets\Plugin::factory(['pluginName' => $request->get('name')])->config() ?>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
