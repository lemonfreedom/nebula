<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $postInfo = \Nebula\Widgets\Post::alloc(['pid' => $request->get('pid', '')])->get() ?>
<div class="container">
    <h1><?= $postInfo['title'] ?></h1>
    <p id="content"></p>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<script>
    var md = window.markdownit();
    document.querySelector('#content').innerHTML = md.render(`> a\n > b\n> s`);
</script>
<?php require __DIR__ . '/footer.php'; ?>
