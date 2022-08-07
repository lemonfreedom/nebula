<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $userList = \Nebula\Widgets\User::allocAlias('users', ['keyword' => $request->get('keyword', '')])->getUserList() ?>
<div class="container">
    <h2 class="nebula-title">新增文章</h2>
    <form class="nebula-form" id="postForm" action="/post/create-post" method="POST">
        <div class="form-item">
            <label class="form-label" for="title">标题</label>
            <input class="nebula-input" id="title" name="title" value="<?= $cache->get('createPostTitle', '') ?>"></input>
        </div>
        <div class="form-item">
            <label class="form-label" for="tid">分类</label>
            <select class="nebula-select" id="tid" name="tid" value="<?= $cache->get('createPostTid', '') ?>">
                <option value="0" selected>分类一</option>
                <option value="1">分类二</option>
            </select>
        </div>
        <div class="form-item">
            <label class="form-label" for="content">内容</label>
            <textarea class="nebula-textarea" id="content" name="content"><?= $cache->get('createPostContent', '') ?></textarea>
        </div>
        <div class="form-tools">
            <div class="nebula-button-group">
                <button type="button" class="nebula-button">保存草稿</button>
                <button type="button" class="nebula-button">预览</button>
                <button type="submit" class="nebula-button">发布文章</button>
            </div>
        </div>
    </form>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
