<?php require __DIR__ . '/common.php'; ?>
<?php $user->hasLogin() || $options->response->redirect('/admin/login.php'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<div class="container">
    <form action="/options/update" method="post">
        <h2 class="page-title">
            <span>设置</span>
            <button class="nebula-button">保存设置</button>
        </h2>
        <div class="page-subtitle">基本设置</div>
        <div class="form-item">
            <label class="form-label" for="title">站点名称</label>
            <input class="nebula-input" id="title" name="title" value="<?= $options->title ?>"></input>
            <div class="form-sublabel">站点的名称将显示在网页的标题处</div>
        </div>
        <div class="form-item">
            <label class="form-label" for="description">站点描述</label>
            <input class="nebula-input" id="description" name="description" value="<?= $options->description ?>"></input>
            <div class="form-sublabel">站点描述将显示在网页代码的头部</div>
        </div>
        <div class="form-item">
            <label class="form-label" for="allowRegister">是否允许注册</label>
            <label class="nebula-switch">
                <input type="checkbox" id="allowRegister" name="allowRegister" value="1" <?= $options->allowRegister === '1' ? 'checked' : '' ?>>
                <span class="slider"></span>
            </label>
            <div class="form-sublabel">允许访问者注册到你的网站</div>
        </div>
    </form>
</div>
<?php require __DIR__ . '/footer.php'; ?>
