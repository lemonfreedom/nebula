<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $postList = \Nebula\Widgets\Post::alloc()->getPostList() ?>
<div class="container">
    <h2 class="page-title">
        <span>分类</span>
        <div class="actions">
            <a class="nebula-button" href="/admin/create-post.php">新增</a>
        </div>
    </h2>
    <div class="nebula_table">
        <table>
            <colgroup>
                <col width="70%">
                <col width="30%">
            </colgroup>
            <thead>
                <tr>
                    <th>名称</th>
                    <th>优先级</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($postList as  $postInfo) : ?>
                    <tr>
                        <td><a href="/admin/preview.php?pid=<?= $postInfo['pid'] ?>"><?= $postInfo['title'] ?></a></td>
                        <td><?= $postInfo['tid'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
