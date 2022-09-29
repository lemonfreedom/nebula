// 显示通知
const notice = (message, type = 'info') => {
    let noticeEl = document.createElement('div');
    noticeEl.classList.add('notice');
    noticeEl.classList.add('notice-' + type);
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
        let mainEl = document.querySelector('.navbar .main');
        mainEl.classList.toggle('open');
        window.scrollTo(0, 0);
        document.body.classList.toggle('mask');
    });
}

// 折叠按钮切换
let buttonDropdownEl = document.querySelectorAll('.button-dropdown');
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

const deleteRows = document.querySelector('#deleteRows');
if (deleteRows) {
    deleteRows.addEventListener('click', function (e) {
        e.preventDefault();
        const checkboxList = document.querySelectorAll('input[type="checkbox"]');
        let id = [];
        checkboxList.forEach(el => el.checked && id.push(el.value));
        fetch('/content/delete-content', {
            method: 'post',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id.join(',')}`,
        }).then(res => res.json()).then(res => {
            if (res.code === 0) {
                location.replace(res.data.redirect);
            } else {
                notice(res.message, 'warning');
            }
        })
    });
}
