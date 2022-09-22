<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<div class="container">
    <div class="title">
        <div>
            <span>主题配置「<?= $option->get('theme')['name'] ?>」</span>
            <a href="/admin/themes.php">返回</a>
        </div>
    </div>
    <form class="form" action="/theme/update-config" method="post">
        <?php \Nebula\Widgets\Theme::factory()->config() ?>
        <div class="form-tools">
            <button class="button" type="submit">保存设置</button>
        </div>
    </form>
</div>
<?php include __DIR__ . '/modules/copyright.php'; ?>
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
