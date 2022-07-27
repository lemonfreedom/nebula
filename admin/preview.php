<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $postInfo = \Nebula\Widgets\Post::alloc(['pid' => $request->get('pid', '')])->get() ?>
<div class="container">
    <h1><?= $postInfo['title'] ?></h1>
    <div><?= $postInfo['content'] ?></div>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
