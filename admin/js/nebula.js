(() => {
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

    // 通知
    let notice = Cookies.get('nebula_notice');
    if (notice) {
        notice = JSON.parse(notice);
        let noticeEl = document.createElement('div');
        noticeEl.classList.add('nebula-notice');
        noticeEl.classList.add('nebula-' + notice.type);
        noticeEl.innerText = notice.message;
        document.body.append(noticeEl);
        Cookies.remove('nebula_notice');
    };
})()
