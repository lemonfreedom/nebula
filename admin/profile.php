<?php require __DIR__ . '/common.php'; ?>
<?php \Nebula\Widgets\Users\Method::factory()->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $action = $request->get('action'); ?>
<?php $userInfo = \Nebula\Widgets\Users\Method::factory(['uid' => $request->get('uid')], 'render')->get(); ?>
<?php null === $userInfo && $response->redirect('/admin'); ?>
<div class="container">
    <div class="nebula-tabs">
        <div class="scroll">
            <div class="tab<?= $action !== 'password' && $action !== 'permission' ? ' active' : '' ?>">
                <a href="/admin/profile.php?uid=<?= $request->get('uid') ?>">用户资料</a>
            </div>
            <div class="tab<?= $action === 'password' ? ' active' : '' ?>">
                <a href="/admin/profile.php?action=password&uid=<?= $request->get('uid') ?>">修改密码</a>
            </div>
            <div class="tab<?= $action === 'permission' ? ' active' : '' ?>">
                <a href="/admin/profile.php?action=permission&uid=<?= $request->get('uid') ?>">权限控制</a>
            </div>
        </div>
    </div>
    <?php if ($action === 'password') : ?>
        <!-- 修改密码 -->
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
    <?php elseif ($action === 'permission' && \Nebula\Widgets\Users\Method::factory()->inRole(['0'])) : ?>
        <!-- 权限控制 -->
        <form class="nebula-form" action="/user/update-permission/<?= $userInfo['uid'] ?>" method="POST">
            <div class="form-item">
                <label class="form-label" for="role">用户角色</label>
                <select class="nebula-select" id="role" name="role">
                    <?php foreach (\Nebula\Widgets\Users\Method::factory()->roleList as $role) : ?>
                        <option value="<?= $role['value'] ?>" <?= $userInfo['role'] === $role['value'] ? 'selected' : '' ?>><?= $role['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-tools">
                <button type="submit" class="nebula-button">保存设置</button>
            </div>
        </form>
    <?php else : ?>
        <!-- 用户资料 -->
        <form class="nebula-form" action="/user/update/<?= $userInfo['uid'] ?>" method="POST">
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
    <?php endif; ?>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
