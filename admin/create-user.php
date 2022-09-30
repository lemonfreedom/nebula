<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<div class="container">
    <div class="title">
        <span>创建用户</span>
        <a href="/admin/users.php">返回</a>
    </div>
    <?= \Nebula\Helpers\Template::form(
        '/user/create-user',
        [
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::input('username'),
                'username',
                '用户名',
            ),
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::input('email', '', 'email'),
                'email',
                '邮箱',
            ),
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::input('password', '', 'password'),
                'password',
                '密码',
            ),
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::select('role', array_map(function ($item) {
                    return [
                        'name' => $item['name'],
                        'value' => $item['tid'],
                    ];
                }, \Nebula\Widgets\Content::factory()->queryTerms())),
                'role',
                '角色',
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
