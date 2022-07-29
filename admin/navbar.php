<?php defined('NEBULA_ROOT_PATH') || exit; ?>
<div class="nebula-navbar">
    <div class="container">
        <h1 class="logo"><?= $options->title ?></h1>
        <div class="main">
            <ul class="menu">
                <li>
                    <a href="/admin/index.php">
                        <i class="bi bi-speedometer2"></i>
                        <span class="text">仪表盘</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/posts.php">
                        <i class="bi bi-file-earmark-text"></i>
                        <span class="text">文章</span>
                    </a>
                </li>
                <li>
                    <a href="/admin/terms.php">
                        <i class="bi bi-file-earmark-text"></i>
                        <span class="text">分类</span>
                    </a>
                </li>
                <?php if ($user->inRole(['0'])) : ?>
                    <li>
                        <a href="/admin/users.php">
                            <i class="bi bi-people"></i>
                            <span class="text">用户</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($user->inRole(['0'])) : ?>
                    <li>
                        <a href="/admin/options.php">
                            <i class="bi bi-gear"></i>
                            <span class="text">设置</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="menu">
                <li>
                    <a href="/admin/profile.php?uid=<?= $user->get('uid') ?>">
                        <i class="bi bi-person"></i>
                        <span class="text">您好，<?= $user->get('nickname') ?></span>
                    </a>
                </li>
                <li>
                    <a href="/user/logout">
                        <i class="bi bi-box-arrow-left"></i>
                        <span class="text">退出登录</span>
                    </a>
                </li>
            </ul>
        </div>
        <div id="menuToggleButton" class="menu-toggle">
            <i class="bi bi-grid"></i>
        </div>
    </div>
</div>
