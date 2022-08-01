<?php $pluginName = Nebula\Request::getInstance()->get('name'); ?>
<div class="nebula-title">
    <div>
        <span>插件配置「<?= $pluginName ?>」</span>
        <a href="/admin/plugins.php">返回</a>
    </div>
</div>
<form class="nebula-form" action="/plugin/update-config" method="post">
    <input type="text" hidden name="pluginName" value="<?= $pluginName ?>">
    <div class="form-item">
        <label class="form-label" for="message">显示内容</label>
        <input class="nebula-input" type="text" name="message" value="<?= $data['message'] ?>">
    </div>
    <div class="form-tools">
        <button class="nebula-button" type="submit">保存设置</button>
    </div>
</form>
