<script>
    // 发送测试邮件
    let sendTestMailEl = document.querySelector("#sendTestMail");
    if (sendTestMailEl) {
        sendTestMailEl.addEventListener('click', function (event) {
            this.innerText = '发送中...';
            fetch('/common/send-test-mail', {
                method: 'POST',
                body: new FormData(document.querySelector('#smtpOptionForm')),
            }).then(res => res.json()).then(res => {
                notice(res.message, res.type);
            }).finally(() => {
                this.innerText = '发送测试邮件';
            })
        });
    }
</script>
