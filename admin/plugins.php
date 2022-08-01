<?php require __DIR__ . '/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $pluginList = \Nebula\Widgets\Plugin::alloc()->getPluginList(); ?>
<div class="container">
    <div class="nebula-title">插件</div>
    <div class="nebula-table">
        <table>
            <colgroup>
                <col width="20%">
                <col width="80%">
            </colgroup>
            <thead>
                <tr>
                    <th>插件名称</th>
                    <th>插件信息</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pluginList as $plugin) : ?>
                    <tr>
                        <td><a href="<?= $plugin['url'] ?>"><?= $plugin['name'] ?></a></td>
                        <td>
                            <div>作者：<a href="<?= $plugin['author_url'] ?>"><?= $plugin['author'] ?></a></div>
                            <div>版本：<?= $plugin['version'] ?></div>
                            <div>描述：<?= $plugin['description'] ?></div>
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
