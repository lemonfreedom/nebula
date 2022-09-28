<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<?php $list = \Nebula\Widgets\Plugin::factory()->getPluginList(); ?>
<div class="container">
    <?= \Nebula\Helpers\Template::tabs(
        [
            ['name' => '文章', 'path' => "/admin/contents.php", 'active' => null, 'has' => true],
            ['name' => '分类', 'path' => "/admin/contents.php?action=cat", 'active' => 'cat', 'has' => true],
        ],
        $action,
        \Nebula\Plugin::factory('admin/contents.php')->tab(['action' => $action])
    ) ?>
    <?php if (null === $action) : ?>
        <div class="tools">
            <a class="button" href="/admin/post.php">发布文章</a>
        </div>
        <div class="table">
            <table>
                <colgroup>
                    <col width="30%">
                    <col width="20%">
                    <col width="30%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <tr>
                        <th>标题</th>
                        <th>作者</th>
                        <th>发布日期</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $item) : ?>
                        <tr>
                            <td><a href="<?= $item['url'] ?>" title="<?= $item['description'] ?>"><?= $item['name'] ?></a></td>
                            <td><?= $item['version'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <ul class="pagination">
            <li class="active"><a href="">1</a></li>
            <li class="active"><a href="">2</a></li>
            <li><span class="more">...</span></li>
            <li><a href="">3</a></li>
            <li><a href="">4</a></li>
        </ul>
    <?php endif; ?>
    <?php \Nebula\Plugin::factory('admin/options.php')->tabContent(['action' => $action]); ?>
</div>
<?php include __DIR__ . '/modules/copyright.php'; ?>
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
