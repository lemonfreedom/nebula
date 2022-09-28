<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<div class="container">
    <div class="title">
        <span>发布文章</span>
        <a href="/admin/contents.php">返回</a>
    </div>
    <?= \Nebula\Helpers\Template::form(
        '/content/create-content',
        [
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::input('title', $cache->get('setPostTitle', '')),
                'title',
                '标题',
            ),
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::textarea('content', $cache->get('setPostContent', '')),
                'content',
                '内容'
            ),
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::select('tid', [
                    ['name' => '分类一', 'value' => '0'],
                    ['name' => '分类二', 'value' => '1'],
                ], ''),
                'tid',
                '分类',
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
