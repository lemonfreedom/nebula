<?php require __DIR__ . '/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<div class="submenu">
    <div class="container">
        <ul class="menu">
            <li><a href="/admin/option-basic.php">基本设置</a></li>
            <li class="active"><a href="/admin/option-theme.php">主题</a></li>
            <li><a href="/admin/option-plugin.php">插件</a></li>
            <li><a href="/admin/option-smtp.php">SMTP 设置</a></li>
        </ul>
    </div>
</div>
<div class="container">
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
