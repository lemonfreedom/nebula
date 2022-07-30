<?php require __DIR__ . '/common.php'; ?>
<?php $user->inRole(['0']) || $response->redirect('/admin'); ?>
<?php require __DIR__ . '/header.php'; ?>
<?php require __DIR__ . '/navbar.php'; ?>
<div class="submenu">
    <div class="container">
        <ul class="menu">
            <li class="active"><a href="/admin/option-basic.php">基本设置</a></li>
            <li><a href="/admin/option-theme.php">主题</a></li>
            <li><a href="/admin/option-plugin.php">插件</a></li>
            <li><a href="/admin/option-smtp.php">SMTP 设置</a></li>
        </ul>
    </div>
</div>
<div class="container">
    <form class="nebula-form" action="/option/update-basic" method="post">
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
            <div class="nebula-radio-group">
                <label class="nebula-radio">
                    <input type="radio" name="allowRegister" value="0" <?= $options->allowRegister === '0' ? 'checked' : '' ?>>
                    <div class="checkmark"></div>
                    <span>否</span>
                </label>
                <label class="nebula-radio">
                    <input type="radio" name="allowRegister" value="1" <?= $options->allowRegister === '1' ? 'checked' : '' ?>>
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
</div>
<?php require __DIR__ . '/copyright.php'; ?>
<?php require __DIR__ . '/common-js.php'; ?>
<?php require __DIR__ . '/footer.php'; ?>
