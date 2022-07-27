<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $postInfo = \Nebula\Widgets\Post::alloc(['pid' => $request->get('pid', '')])->get() ?>
<div class="container">
    <h1><?= $postInfo['title'] ?></h1>
    <div id="preview-container"></div>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<script>
    if (document.querySelector('#preview-container')) {
        const quill = new Quill('#preview-container', {
            modules: {
                formula: true,
                syntax: true,
                toolbar: false,
            },
            readOnly: true,
            theme: 'snow'
        });
        quill.setContents(<?= $postInfo['content'] ?>);
    }
</script>
<?php require __DIR__ . '/footer.php'; ?>
