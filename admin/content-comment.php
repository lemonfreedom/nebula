<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $postList = \Nebula\Widgets\Post::alloc()->getPostList() ?>
<div class="submenu">
    <div class="container">
        <ul class="menu">
            <li><a href="/admin/content-post.php">文章</a></li>
            <li class="active"><a href="/admin/content-comment.php">评论</a></li>
            <li><a href="/admin/content-file.php">文件</a></li>
        </ul>
    </div>
</div>
<div class="container">
    <div class="nebula-tools">
        <form action="/admin/content-post.php" method="get">
            <input class="nebula-input" type="text" name="keyword" placeholder="输入关键字">
            <button class="nebula-button">搜索</button>
        </form>
        <div class="nebula-button-group">
            <div class="nebula-button-dropdown">
                <span>选择项</span>
                <ul class="dropdown-menu">
                    <li><a href="">删除</a></li>
                    <li><a href="">标记为中国</a></li>
                </ul>
            </div>
            <a class="nebula-button" href="/admin/create-post.php">新增</a>
        </div>
    </div>
    <div class="nebula-table">
        <table>
            <colgroup>
                <col width="10%">
                <col width="30%">
                <col width="20%">
                <col width="20%">
                <col width="20%">
            </colgroup>
            <thead>
                <tr>
                    <th>#</th>
                    <th>标题</th>
                    <th>作者</th>
                    <th>分类</th>
                    <th>日期</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($postList as  $postInfo) : ?>
                    <tr>
                        <td>
                            <label class="nebula-checkbox">
                                <input type="checkbox">
                                <div class="checkmark"></div>
                            </label>
                        </td>
                        <td><a href="/admin/preview.php?pid=<?= $postInfo['pid'] ?>"><?= $postInfo['title'] ?></a></td>
                        <td><?= $postInfo['tid'] ?></td>
                        <td><?= $postInfo['title'] ?></td>
                        <td>2022-08-22 11:32:11</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
