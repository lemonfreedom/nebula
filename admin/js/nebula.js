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

    // 富文本编辑器
    if (document.querySelector('#editor-container')) {
        const quill = new Quill('#editor-container', {
            modules: {
                formula: true,
                syntax: true,
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'align': [] }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    ['blockquote', 'code-block'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'script': 'sub' }, { 'script': 'super' }],
                    ['link', 'image', 'video', 'formula'],
                    ['clean'],
                ],
            },
            theme: 'snow'
        });

        document.querySelector('#postSubmitButton').addEventListener('click', function () {
            const postForm = document.querySelector('#postForm');
            postForm.content.value = JSON.stringify(quill.getContents());
            postForm.submit();
        });
    }
})()
