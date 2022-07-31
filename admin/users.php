<?php require __DIR__ . '/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $userList = \Nebula\Widgets\User::allocAlias('userList', ['keyword' => $request->get('keyword', '')])->getUserList() ?>
<div class="container">
    <div class="nebula-title">用户</div>
    <div class="nebula-tools">
        <form action="/admin/users.php" method="get">
            <input class="nebula-input" type="text" name="keyword" placeholder="输入关键字">
            <button class="nebula-button">搜索</button>
        </form>
        <div class="nebula-button-dropdown">
            <span>选择项</span>
            <ul class="dropdown-menu">
                <li><a href="">删除</a></li>
                <li><a href="">标记为中国</a></li>
            </ul>
        </div>
    </div>
    <div class="nebula-table">
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
                            <label class="nebula-checkbox">
                                <input checked="checked" type="checkbox">
                                <div class="checkmark"></div>
                            </label>
                        </td>
                        <td><a href="/admin/profile.php?uid=<?= $userInfo['uid'] ?>"><?= $userInfo['username'] ?></a></td>
                        <td><?= $userInfo['nickname'] ?></td>
                        <td><?= $userInfo['email'] ?></td>
                        <td><?= $user->getRoleName($userInfo['role']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
