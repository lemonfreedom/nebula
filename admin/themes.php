<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<?php $themeList = \Nebula\Widgets\Theme::factory()->getThemeList(); ?>
<div class="container theme">
    <?= \Nebula\Helpers\Template::tabs(
        [
            ['name' => '主题', 'path' => "/admin/themes.php", 'active' => null, 'has' => true],
        ],
        $action,
        \Nebula\Plugin::factory('admin/themes.php')->tab(['action' => $action])
    ) ?>
    <?php if (null === $action) : ?>
        <div class="table">
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
                                    <?= $theme['description'] ?>
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
    <?php endif; ?>
</div>
<?php include __DIR__ . '/modules/copyright.php'; ?>
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
