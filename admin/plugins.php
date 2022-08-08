<?php require __DIR__ . '/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $pluginList = \Nebula\Widgets\Plugin::factory()->getPluginList(); ?>
<div class="container">
    <div class="nebula-title">插件</div>
    <div class="nebula-table">
        <table>
            <colgroup>
                <col width="30%">
                <col width="20%">
                <col width="30%">
                <col width="20%">
            </colgroup>
            <thead>
                <tr>
                    <th>名称</th>
                    <th>版本</th>
                    <th>作者</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pluginList as $plugin) : ?>
                    <tr>
                        <td><a href="<?= $plugin['url'] ?>"><?= $plugin['name'] ?></a></td>
                        <td><?= $plugin['version'] ?></td>
                        <td><a href="<?= $plugin['author_url'] ?>"><?= $plugin['author'] ?></a></td>
                        <td>
                            <?php if ($plugin['is_activated']) : ?>
                                <a href="/admin/plugin-config.php?name=<?= $plugin['dir'] ?>">设置</a>
                                <a href="/plugin/disabled/<?= $plugin['dir'] ?>">禁用</a>
                            <?php else : ?>
                                <a href="/plugin/enable/<?= $plugin['dir'] ?>">启用</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
