<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<?php $userList = \Nebula\Widgets\User::factory(['keyword' => $request->get('keyword', '')], 'render')->queryUsers() ?>
<div class="container">
    <?= \Nebula\Helpers\Template::tabs(
        [
            ['name' => '用户', 'path' => "/admin/users.php", 'active' => null, 'has' => true],
            ['name' => '角色', 'path' => "/admin/users.php?action=role", 'active' => 'role', 'has' => true],
        ],
        $action,
        \Nebula\Plugin::factory('admin/user.php')->tab(['action' => $action])
    ) ?>
    <?php if (null === $action) : ?>
        <div class="tools">
            <a class="button" href="/admin/create-user.php">创建用户</a>
            <div class="group">
                <div class="button-dropdown">
                    <span>选择项</span>
                    <ul class="dropdown-menu">
                        <li><a href="">删除</a></li>
                    </ul>
                </div>
                <form class="group" action="/admin/users.php" method="GET">
                    <input class="input" type="text" name="keyword" placeholder="输入关键字">
                    <button class="button">搜索</button>
                </form>
            </div>
        </div>
        <div class="table">
            <table>
                <colgroup>
                    <col width="10%">
                    <col width="20%">
                    <col width="20%">
                    <col width="30%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>用户名</th>
                        <th>昵称</th>
                        <th>邮箱</th>
                        <th>角色</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userList as  $userInfo) : ?>
                        <tr>
                            <td>
                                <label class="checkbox">
                                    <input checked="checked" type="checkbox">
                                    <div class="checkmark"></div>
                                </label>
                            </td>
                            <td><a href="/admin/profile.php?uid=<?= $userInfo['uid'] ?>"><?= $userInfo['username'] ?></a></td>
                            <td><?= $userInfo['nickname'] ?></td>
                            <td><?= $userInfo['email'] ?></td>
                            <td><?= $user->roleParse($userInfo['rid']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= \Nebula\Helpers\Template::pagination('/admin/users.php', [], $request->get('page', 1), ceil(\Nebula\Widgets\User::factory()->queryUserCount() / 8)) ?>
    <?php elseif ('role' === $action) : ?>
        <div class="tools">
            <a class="button" href="/admin/create-role.php">新建角色</a>
            <div class="button-dropdown">
                <span>选择项</span>
                <ul class="dropdown-menu">
                    <li><a href="">删除</a></li>
                </ul>
            </div>
        </div>
        <div class="table">
            <table>
                <colgroup>
                    <col width="10%">
                    <col width="20%">
                    <col width="20%">
                    <col width="30%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>用户名</th>
                        <th>昵称</th>
                        <th>邮箱</th>
                        <th>角色</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userList as  $userInfo) : ?>
                        <tr>
                            <td>
                                <label class="checkbox">
                                    <input checked="checked" type="checkbox">
                                    <div class="checkmark"></div>
                                </label>
                            </td>
                            <td><a href="/admin/profile.php?uid=<?= $userInfo['uid'] ?>"><?= $userInfo['username'] ?></a></td>
                            <td><?= $userInfo['nickname'] ?></td>
                            <td><?= $userInfo['email'] ?></td>
                            <td><?= $user->roleParse($userInfo['rid']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/modules/copyright.php'; ?>
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
