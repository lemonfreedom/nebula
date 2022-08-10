<div class="nebula-title">
    <div>
        <span>主题配置「<?= \Nebula\Widgets\Option::factory()->get('theme')['name'] ?>」</span>
        <a href="/admin/themes.php">返回</a>
    </div>
</div>
<form class="nebula-form" action="/theme/update-config" method="post">
    <div class="form-item">
        <label class="form-label" for="val1">参数一</label>
        <input class="nebula-input" type="text" name="val1" value="<?= $data['val1'] ?>">
    </div>
    <div class="form-item">
        <label class="form-label" for="val2">参数二</label>
        <input class="nebula-input" type="text" name="val2" value="<?= $data['val2'] ?>">
    </div>
    <div class="form-item">
        <label class="form-label" for="val2">参数三</label>
        <input class="nebula-input" type="text" name="val3" value="<?= $data['val3'] ?>">
    </div>
    <div class="form-item">
        <label class="form-label" for="val2">参数四</label>
        <input class="nebula-input" type="text" name="val4" value="<?= $data['val4'] ?>">
    </div>
    <div class="form-item">
        <label class="form-label" for="val2">参数五</label>
        <input class="nebula-input" type="text" name="val5" value="<?= $data['val5'] ?>">
    </div>
    <div class="form-tools">
        <button class="nebula-button" type="submit">保存设置</button>
    </div>
</form>
