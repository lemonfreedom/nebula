<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<div class="container">
    <?= \Nebula\Helpers\Template::tabs(
        [
            ['name' => '基本设置', 'path' => "/admin/options.php", 'active' => null, 'has' => true],
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
    <?php endif; ?>
    <?php \Nebula\Plugin::factory('admin/options.php')->tabContent(['action' => $action]); ?>
</div>
<?php include __DIR__ . '/modules/copyright.php'; ?>
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
