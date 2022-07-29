(() => {
    // 显示通知
    const notice = (message, type = 'info') => {
        let noticeEl = document.createElement('div');
        noticeEl.classList.add('nebula-notice');
        noticeEl.classList.add('nebula-' + type);
        noticeEl.innerText = message;
        document.body.append(noticeEl);
        setTimeout(() => {
            document.body.removeChild(noticeEl);
        }, 2000);
    }

    // 菜单切换
    let menuToggleButtonEl = document.querySelector('#menuToggleButton');
    if (menuToggleButtonEl) {
        menuToggleButtonEl.addEventListener('click', function () {
            let mainEl = document.querySelector('.nebula-navbar .main');
            mainEl.classList.toggle('open');
            window.scrollTo(0, 0);
            document.body.classList.toggle('mask');
        });
    }

    // 折叠按钮切换
    let buttonDropdownEl = document.querySelectorAll('.nebula-button-dropdown');
    buttonDropdownEl.forEach(el => {
        el.addEventListener('click', function () {
            el.classList.toggle('open');
        });
    });

    // cookie 通知
    let cookieNotice = Cookies.get('nebula_notice');
    if (cookieNotice) {
        cookieNotice = JSON.parse(cookieNotice);
        notice(cookieNotice.message, cookieNotice.type)
        Cookies.remove('nebula_notice');
    };

    // 发送验证码
    let sendCaptchaEl = document.querySelector("#sendCaptcha");
    if (sendCaptchaEl) {
        sendCaptchaEl.addEventListener('click', function (event) {
            this.innerText = '发送中...';
            let formData = new FormData();
            formData.append('email', document.querySelector('input[type="email"]').value)
            fetch('/user/send-register-captcha', {
                method: 'POST',
                body: formData,
            }).then(res => res.json()).then(res => {
                if (res.errorCode === 0) {
                    this.innerText = '发送成功';
                    this.disabled = true;
                } else {
                    notice(res.message, res.type);
                    this.innerText = '发送';
                }
            })
        });
    }

    // 发送测试邮件
    let sendTestMailEl = document.querySelector("#sendTestMail");
    if (sendTestMailEl) {
        sendTestMailEl.addEventListener('click', function (event) {
            this.innerText = '发送中...';
            fetch('/user/send-test-mail').then(res => res.json()).then(res => {
                notice(res.message, res.type);
            }).finally(() => {
                this.innerText = '发送测试邮件';
            })
        });
    }
})()
