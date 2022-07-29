<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $userInfo = \Nebula\Widgets\User::allocAlias($request->get('uid'), ['uid' => $request->get('uid')])->get(); ?>
<?php null === $userInfo && $response->redirect('/admin'); ?>
<div class="container">
    <h2 class="page-title">
        <span>用户设置</span>
    </h2>
    <form class="nebula-form" action="/user/update/<?= $userInfo['uid'] ?>" method="post">
        <div class="page-subtitle">
            <span>用户资料</span>
            <button class="nebula-button">保存设置</button>
        </div>
        <div class="form-item">
            <label class="form-label" for="nickname">昵称</label>
            <input class="nebula-input" id="nickname" name="nickname" value="<?= $userInfo['nickname'] ?>"></input>
            <label class="form-sublabel">若昵称为空，则显示用户名</label>
        </div>
        <div class="form-item">
            <label class="form-label" for="username">用户名</label>
            <input class="nebula-input" id="username" name="username" value="<?= $userInfo['username'] ?>"></input>
            <label class="form-sublabel">系统登录用户名</label>
        </div>
        <div class="form-item">
            <label class="form-label" for="email">邮箱</label>
            <input class="nebula-input" id="email" name="email" value="<?= $userInfo['email'] ?>"></input>
        </div>
    </form>
    <form class="nebula-form" action="/user/update-password/<?= $userInfo['uid'] ?>" method="post">
        <div class="page-subtitle">
            <span>密码修改</span>
            <button class="nebula-button">保存设置</button>
        </div>
        <div class="form-item">
            <label class="form-label" for="password">密码</label>
            <input class="nebula-input" type="password" id="password" name="password" value="" autocomplete></input>
        </div>
        <div class="form-item">
            <label class="form-label" for="confirmPassword">确认密码</label>
            <input class="nebula-input" type="password" id="confirmPassword" name="confirmPassword" value="" autocomplete></input>
        </div>
    </form>
    <?php if ($user->inRole(['0'])) : ?>
        <form class="nebula-form" action="/user/update-permission/<?= $userInfo['uid'] ?>" method="post">
            <div class="page-subtitle">
                <span>权限控制</span>
                <button class="nebula-button">保存设置</button>
            </div>
            <div class="form-item">
                <label class="form-label" for="role">用户角色</label>
                <select class="nebula-select" id="role" name="role">
                    <?php foreach ($user->roleList as $role) : ?>
                        <option value="<?= $role['value'] ?>" <?= $userInfo['role'] === $role['value'] ? 'selected' : '' ?>><?= $role['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
