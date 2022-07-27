<?php require __DIR__ . '/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $userList = \Nebula\Widgets\User::allocAlias('userList', ['keyword' => $request->get('keyword', '')])->getUserList() ?>
<div class="container">
    <h2 class="page-title">
        <span>用户</span>
        <form class="actions" action="/admin/users.php" method="get">
            <input class="nebula-input filter" type="text" name="keyword" placeholder="输入关键字">
            <button class="nebula-button">搜索</button>
        </form>
    </h2>
    <div class="nebula_table">
        <table>
            <colgroup>
                <col width="20%">
                <col width="32%">
                <col width="32%">
                <col width="16%">
            </colgroup>
            <thead>
                <tr>
                    <th>用户名</th>
                    <th>昵称</th>
                    <th>邮箱</th>
                    <th>角色</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userList as  $userInfo) : ?>
                    <tr>
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
<?php require __DIR__ . '/footer.php'; ?>
