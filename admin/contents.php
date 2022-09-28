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
            <form class="group" action="/admin/users.php" method="GET">
                <input class="input" type="text" name="keyword" placeholder="输入关键字">
                <button class="button">搜索</button>
            </form>
            <div class="group">
                <div class="button-dropdown">
                    <span>选择项</span>
                    <ul class="dropdown-menu">
                        <li><a href="">删除</a></li>
                    </ul>
                </div>
                <a class="button" href="/admin/post.php">发布文章</a>
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
                        <th>标题</th>
                        <th>作者</th>
                        <th>发布日期</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($list as $item) : ?>
                        <tr>
                            <td>
                                <label class="checkbox">
                                    <input checked="checked" type="checkbox">
                                    <div class="checkmark"></div>
                                </label>
                            </td>
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
