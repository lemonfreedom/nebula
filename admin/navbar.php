<?php defined('NEBULA_ROOT_PATH') || exit; ?>
<div class="nebula-navbar">
    <div class="container">
        <div class="left">
            <div class="logo">Nebula</div>
            <ul class="menu">
                <li><a href="">仪表盘</a></li>
                <li><a href="">设置</a></li>
            </ul>
        </div>
        <button class="menu-toggle material-symbols-rounded">
            menu
        </button>
        <ul class="menu">
            <li><a href="">你好，<?= $user->username ?></a></li>
            <li><a href="/user/logout">退出登陆</a></li>
        </ul>
    </div>
</div>
