<?php include __DIR__ . '/modules/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<?php $action = $request->get('action'); ?>
<div class="container">
    <div class="nebula-tabs">
        <div class="scroll">
            <div class="tab<?= null === $action ? ' active' : '' ?>">
                <a href="/admin/contents.php">文章</a>
            </div>
            <div class="tab<?= 'cat' === $action ? ' active' : '' ?>">
                <a href="/admin/contents.php?action=cat">分类</a>
            </div>
            <?php \Nebula\Plugin::factory('admin/contents.php')->tab(['action' => $action]); ?>
        </div>
    </div>
    <?php if (null === $action) : ?>
        <form class="nebula-form" action="/option/update/basic" method="POST">
            <div class="form-item">
                <label class="form-label" for="title">站点名称</label>
                <input class="nebula-input" id="title" name="title" value="<?= $option->get('title') ?>"></input>
                <div class="form-sublabel">站点的名称将显示在网页的标题处</div>
            </div>
            <div class="form-item">
                <label class="form-label" for="description">站点描述</label>
                <input class="nebula-input" id="description" name="description" value="<?= $option->get('description') ?>"></input>
                <div class="form-sublabel">站点描述将显示在网页代码的头部</div>
            </div>
            <div class="form-item">
                <label class="form-label" for="allowRegister">是否允许注册</label>
                <div class="nebula-radio-group">
                    <label class="nebula-radio">
                        <input type="radio" name="allowRegister" value="0" <?= !$option->get('allowRegister') ? 'checked' : '' ?>>
                        <div class="checkmark"></div>
                        <span>否</span>
                    </label>
                    <label class="nebula-radio">
                        <input type="radio" name="allowRegister" value="1" <?= $option->get('allowRegister') ? 'checked' : '' ?>>
                        <div class="checkmark"></div>
                        <span>是</span>
                    </label>
                </div>
                <div class="form-sublabel">允许访问者注册到你的网站</div>
            </div>
            <div class="form-tools">
                <button class="nebula-button">保存设置</button>
            </div>
        </form>
    <?php endif; ?>
    <?php \Nebula\Plugin::factory('admin/options.php')->tabContent(['action' => $action]); ?>
</div>
<?php include __DIR__ . '/modules/copyright.php'; ?>
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
