<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<?php $action = $request->get('action'); ?>
<?php $userInfo = \Nebula\Widgets\User::factory(['uid' => $request->get('uid')], 'render')->get(); ?>
<?php null === $userInfo && $response->redirect('/admin'); ?>
<div class="container">
    <div class="nebula-tabs">
        <div class="scroll">
            <div class="tab<?= null === $action ? ' active' : '' ?>">
                <a href="/admin/profile.php?uid=<?= $request->get('uid') ?>">用户资料</a>
            </div>
            <div class="tab<?= 'password' === $action ? ' active' : '' ?>">
                <a href="/admin/profile.php?action=password&uid=<?= $request->get('uid') ?>">修改密码</a>
            </div>
            <?php if (\Nebula\Widgets\User::factory()->inRole(['0'])) : ?>
                <div class="tab<?= 'permission' === $action ? ' active' : '' ?>">
                    <a href="/admin/profile.php?action=permission&uid=<?= $request->get('uid') ?>">权限控制</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if (null === $action) : ?>
        <form class="nebula-form" action="/user/update-info/<?= $userInfo['uid'] ?>" method="POST">
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
            <div class="form-tools">
                <button type="submit" class="nebula-button">保存设置</button>
            </div>
        </form>
    <?php elseif ('password' === $action) : ?>
        <form class="nebula-form" action="/user/update-password/<?= $userInfo['uid'] ?>" method="POST">
            <div class="form-item">
                <label class="form-label" for="password">密码</label>
                <input class="nebula-input" type="password" id="password" name="password" value="" autocomplete></input>
            </div>
            <div class="form-item">
                <label class="form-label" for="confirmPassword">确认密码</label>
                <input class="nebula-input" type="password" id="confirmPassword" name="confirmPassword" value="" autocomplete></input>
            </div>
            <div class="form-tools">
                <button type="submit" class="nebula-button">保存设置</button>
            </div>
        </form>
    <?php elseif ('permission' === $action && \Nebula\Widgets\User::factory()->inRole(['0'])) : ?>
        <form class="nebula-form" action="/user/update-permission/<?= $userInfo['uid'] ?>" method="POST">
            <div class="form-item">
                <label class="form-label" for="role">用户角色</label>
                <select class="nebula-select" id="role" name="role">
                    <?php foreach (\Nebula\Widgets\User::factory()->roleList as $role) : ?>
                        <option value="<?= $role['value'] ?>" <?= $role['value'] === $userInfo['role'] ? 'selected' : '' ?>><?= $role['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-tools">
                <button type="submit" class="nebula-button">保存设置</button>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/modules/copyright.php'; ?>
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
