<?php require __DIR__ . '/common.php'; ?>
<?php \Nebula\Widgets\Users\Method::factory()->inRole(['0']) || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<?php $themeList = \Nebula\Widgets\Themes\Method::factory()->getThemeList(); ?>
<div class="container theme">
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
                            <div class="theme-title">
                                <span>名称：</span>
                                <a href="<?= $theme['url'] ?>"><?= $theme['name'] ?></a>
                            </div>
                            <div class="theme-info-row">
                                <div>
                                    <span>版本：</span>
                                    <span><?= $theme['version'] ?></span>
                                </div>
                                <div>
                                    <span>作者：</span>
                                    <a href="<?= $theme['author_url'] ?>"><?= $theme['author'] ?></a>
                                </div>
                            </div>
                            <div class="theme-info-row">
                                <?php if ($theme['is_activated']) : ?>
                                    <?php if ($theme['is_config']) : ?>
                                        <a href="/admin/theme-config.php">设置</a>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <a href="/theme/enable/<?= $theme['dir'] ?>">启用</a>
                                <?php endif; ?>
                            </div>
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
