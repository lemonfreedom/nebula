<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<div class="container">
    <?= \Nebula\Helpers\Template::tabs(
        [
            ['name' => '基本设置', 'path' => "/admin/options.php", 'active' => null, 'has' => true],
            ['name' => '主题', 'path' => "/admin/options.php?action=theme", 'active' => 'theme', 'has' => true],
            ['name' => '插件', 'path' => "/admin/options.php?action=plugin", 'active' => 'plugin', 'has' => true],
        ],
        $action,
        \Nebula\Plugin::factory('admin/options.php')->tab(['action' => $action])
    ) ?>
    <?php if (null === $action) : ?>
        <?= \Nebula\Helpers\Template::form(
            '/option/update/basic',
            [
                \Nebula\Helpers\Template::formItem(
                    \Nebula\Helpers\Template::input('title', $option->get('title')),
                    'title',
                    '站点名称',
                    '站点的名称将显示在网页的标题处'
                ),
                \Nebula\Helpers\Template::formItem(
                    \Nebula\Helpers\Template::input('description', $option->get('description')),
                    'description',
                    '站点描述',
                    '站点描述将显示在网页代码的头部'
                ),
                \Nebula\Helpers\Template::formItem(
                    \Nebula\Helpers\Template::radio('allowRegister', [
                        ['name' => '否', 'value' => '0'],
                        ['name' => '是', 'value' => '1'],
                    ], $option->get('allowRegister')),
                    'allowRegister',
                    '是否允许注册',
                    '允许访问者注册到你的网站'
                ),
                \Nebula\Helpers\Template::createElement(
                    'div',
                    ['class' => 'form-tools'],
                    \Nebula\Helpers\Template::button('保存设置', 'submit')
                )
            ]
        ) ?>
    <?php elseif ('theme' === $action) : ?>
        <?php $list = \Nebula\Widgets\Theme::factory()->queryThemes(); ?>
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
                    <?php foreach ($list as $item) : ?>
                        <tr>
                            <td>
                                <div>
                                    <img width="100%" src="<?= $item['screenshot_url'] ?>" alt="">
                                </div>
                            </td>
                            <td>
                                <div class="theme-title">
                                    <span>名称：</span>
                                    <a href="<?= $item['url'] ?>"><?= $item['name'] ?></a>
                                </div>
                                <div class="theme-info-row">
                                    <?= $item['description'] ?>
                                </div>
                                <div class="theme-info-row">
                                    <div>
                                        <span>版本：</span>
                                        <span><?= $item['version'] ?></span>
                                    </div>
                                    <div>
                                        <span>作者：</span>
                                        <a href="<?= $item['author_url'] ?>"><?= $item['author'] ?></a>
                                    </div>
                                </div>
                                <div class="theme-info-row">
                                    <?php if ($item['is_activated']) : ?>
                                        <?php if ($item['is_config']) : ?>
                                            <a href="/admin/theme-config.php">设置</a>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <a href="/theme/enable/<?= $item['dir'] ?>">启用</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ('plugin' === $action) : ?>
        <?php $list = \Nebula\Widgets\Plugin::factory()->queryPlugins(); ?>
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
                        <th>名称</th>
                        <th>版本</th>
                        <th>作者</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $item) : ?>
                        <tr>
                            <td><a href="<?= $item['url'] ?>" title="<?= $item['description'] ?>"><?= $item['name'] ?></a></td>
                            <td><?= $item['version'] ?></td>
                            <td><a href="<?= $item['author_url'] ?>"><?= $item['author'] ?></a></td>
                            <td>
                                <?php if ($item['is_activated']) : ?>
                                    <?php if ($item['is_config']) : ?>
                                        <a href="/admin/plugin-config.php?name=<?= $item['dir'] ?>">设置</a>
                                    <?php endif; ?>
                                    <a href="/plugin/disabled/<?= $item['dir'] ?>">禁用</a>
                                <?php else : ?>
                                    <a href="/plugin/enable/<?= $item['dir'] ?>">启用</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <?php \Nebula\Plugin::factory('admin/options.php')->tabContent(['action' => $action]); ?>
</div>
<?php include __DIR__ . '/modules/copyright.php'; ?>
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
