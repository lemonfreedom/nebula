<div class="form-item">
    <div class="group">
        <input class="nebula-input" type="email" name="email" placeholder="邮箱" value="<?= $cache->get('registerEmail', '') ?>">
        <button id="sendCaptcha" type="button" class="nebula-button">发送</button>
    </div>
</div>
<div class="form-item">
    <input class="nebula-input" type="text" name="code" placeholder="验证码" value="<?= $cache->get('registerCode', '') ?>">
</div>
