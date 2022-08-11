<?php include __DIR__ . '/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php $plugin = \Nebula\Widgets\Plugin::factory(['pluginName' => $request->get('name')]); ?>
<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/navbar.php'; ?>
<div class="container">
    <div class="nebula-title">
        <div>
            <span>插件配置「<?= $plugin->get('name') ?>」</span>
            <a href="/admin/plugins.php">返回</a>
        </div>
    </div>
    <form class="nebula-form" action="/plugin/update-config" method="post">
        <input type="text" hidden name="pluginName" value="<?= $request->get('name') ?>">
        <?php \Nebula\Widgets\Plugin::factory()->config() ?>
        <div class="form-tools">
            <button class="nebula-button" type="submit">保存设置</button>
        </div>
    </form>
</div>
<?php include __DIR__ . '/copyright.php'; ?>
<?php include __DIR__ . '/common-js.php'; ?>
<?php include __DIR__ . '/footer.php'; ?>
