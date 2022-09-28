<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<div class="container">
    <div class="title">
        <span>新建分类</span>
        <a href="/admin/contents.php?action=category">返回</a>
    </div>
    <?= \Nebula\Helpers\Template::form(
        '/content/create-term',
        [
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::input('name', $cache->get('createCategoryName', '')),
                'name',
                '名称',
            ),
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::input('slug', $cache->get('createCategorySlug', '')),
                'slug',
                '缩略名'
            ),
            \Nebula\Helpers\Template::createElement(
                'div',
                ['class' => 'form-tools'],
                \Nebula\Helpers\Template::button('保存设置', 'submit')
            )
        ]
    ) ?>
</div>
<?php include __DIR__ . '/modules/copyright.php'; ?>
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
