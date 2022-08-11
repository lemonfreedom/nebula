<div class="form-item">
    <label class="form-label" for="showSQL">是否显示 SQL</label>
    <div class="nebula-radio-group">
        <label class="nebula-radio">
            <input type="radio" name="showSQL" value="0" <?= '0' === $data['showSQL'] ? 'checked' : '' ?>>
            <div class="checkmark"></div>
            <span>否</span>
        </label>
        <label class="nebula-radio">
            <input type="radio" name="showSQL" value="1" <?= '1' === $data['showSQL'] ? 'checked' : '' ?>>
            <div class="checkmark"></div>
            <span>是</span>
        </label>
    </div>
</div>
