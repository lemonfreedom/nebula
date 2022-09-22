<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<?php $userInfo = \Nebula\Widgets\User::factory(['uid' => $request->get('uid')], 'render')->get(); ?>
<?php null === $userInfo && $response->redirect('/admin'); ?>
<div class="container">
    <?= \Nebula\Helpers\Template::tabs(
        [
            ['name' => '用户资料', 'path' => "/admin/profile.php?uid={$request->get('uid')}", 'active' => null, 'has' => true],
            ['name' => '修改密码', 'path' => "/admin/profile.php?action=password&uid={$request->get('uid')}", 'active' => 'password', 'has' => true],
            ['name' => '权限控制', 'path' => "/admin/profile.php?action=permission&uid={$request->get('uid')}", 'active' => 'permission', 'has' => \Nebula\Widgets\User::factory()->inRole(['0'])],
        ],
        $action,
        \Nebula\Plugin::factory('admin/profile.php')->tab(['action' => $action])
    ) ?>
    <?php if (null === $action) : ?>
        <!-- 用户资料 -->
        <?= \Nebula\Helpers\Template::form("/user/update-info/{$userInfo['uid']}", [
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::input('nickname', $userInfo['nickname']),
                'nickname',
                '昵称',
                '若昵称为空，则显示用户名'
            ),
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::input('username', $userInfo['username']),
                'username',
                '用户名',
                '系统登录用户名'
            ),
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::input('email', $userInfo['email']),
                'email',
                '邮箱',
            ),
            \Nebula\Helpers\Template::createElement(
                'div',
                ['class' => 'form-tools'],
                \Nebula\Helpers\Template::button('保存设置', 'submit')
            )
        ]) ?>
    <?php elseif ('password' === $action) : ?>
        <!-- 修改密码 -->
        <?= \Nebula\Helpers\Template::form("/user/update-password/{$userInfo['uid']}", [
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::input('password', '', 'password'),
                'password',
                '密码',
            ),
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::input('confirmPassword', '', 'password'),
                'confirmPassword',
                '确认密码',
            ),
            \Nebula\Helpers\Template::createElement(
                'div',
                ['class' => 'form-tools'],
                \Nebula\Helpers\Template::button('保存设置', 'submit')
            )
        ]) ?>
    <?php elseif ('permission' === $action && \Nebula\Widgets\User::factory()->inRole(['0'])) : ?>
        <!-- 权限控制 -->
        <?= \Nebula\Helpers\Template::form("/user/update-permission/{$userInfo['uid']}", [
            \Nebula\Helpers\Template::formItem(
                \Nebula\Helpers\Template::select('role', \Nebula\Widgets\User::factory()->roleList, $userInfo['role']),
                'role',
                '用户角色',
            ),
            \Nebula\Helpers\Template::createElement(
                'div',
                ['class' => 'form-tools'],
                \Nebula\Helpers\Template::button('保存设置', 'submit')
            )
        ]) ?>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/modules/copyright.php'; ?>
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
