<?php require __DIR__ . '/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $themeList = \Nebula\Widgets\Theme::alloc()->getThemeList(); ?>
<div class="container">
    <div class="nebula-title">主题</div>
    <div class="nebula-table">
        <table>
            <colgroup>
                <col width="30%">
                <col width="70%">
            </colgroup>
            <thead>
                <tr>
                    <th>截图</th>
                    <th>详情</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($themeList as $theme) : ?>
                    <tr>
                        <td>
                            <div>
                                <img width="100%" src="<?= $theme['screenshot_url'] ?>" alt="">
                            </div>
                        </td>
                        <td>
                            <a href="<?= $theme['url'] ?>"><?= $theme['name'] ?></a>
                            <?= $theme['version'] ?>
                            <a href="<?= $theme['author_url'] ?>"><?= $theme['author'] ?></a>
                            <?php if ($theme['is_activated']) : ?>
                                <a href="/admin/theme-config.php">设置</a>
                            <?php else : ?>
                                <a href="/plugin/enable/<?= $theme['dir'] ?>">启用</a>
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
