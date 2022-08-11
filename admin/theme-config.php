<?php require __DIR__ . '/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<div class="container">
    <div class="nebula-title">
        <div>
            <span>主题配置「<?= $option->get('theme')['name'] ?>」</span>
            <a href="/admin/themes.php">返回</a>
        </div>
    </div>
    <form class="nebula-form" action="/theme/update-config" method="post">
        <?php \Nebula\Widgets\Theme::factory()->config() ?>
        <div class="form-tools">
            <button class="nebula-button" type="submit">保存设置</button>
        </div>
    </form>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
