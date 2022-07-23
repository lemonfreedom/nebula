(() => {
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
    }
})()
