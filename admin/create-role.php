<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<div class="container">
    <div class="title">
        <span>新建角色</span>
        <a href="/admin/users.php?action=role">返回</a>
    </div>
    <?= \Nebula\Helpers\Template::form(
        '/user/create-role',
        [
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::input('name'),
                'name',
                '名称',
            ),
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::checkbox('auth', $option->get('auth')),
                'auth',
                '权限',
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
