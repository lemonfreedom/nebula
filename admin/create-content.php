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
                \Nebula\Helpers\Template::select('tid', array_map(function ($item) {
                    return [
                        'name' => $item['name'],
                        'value' => $item['tid'],
                    ];
                }, \Nebula\Widgets\Content::factory()->queryTerms()), ''),
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
