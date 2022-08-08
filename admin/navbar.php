<?php defined('NEBULA_ROOT_PATH') || exit; ?>
<div class="nebula-navbar">
    <div class="container">
        <h1 class="logo"><?= $option->get('title') ?></h1>
        <div class="main">
            <ul class="menu">
                <li class="<?= $request->currentIndex === 'index.php' ? 'active' : '' ?>">
                    <a href="/admin/index.php">
                        <i class="bi bi-speedometer2"></i>
                        <span class="text">仪表盘</span>
                    </a>
                </li>
                <?php if (\Nebula\Widgets\Users\Method::factory()->inRole(['0'])) : ?>
                    <li class="<?= $request->currentIndex === 'users.php' ? 'active' : '' ?>">
                        <a href="/admin/users.php">
                            <i class="bi bi-people"></i>
                            <span class="text">用户</span>
                        </a>
                    </li>
                    <li class="<?= $request->currentIndex === 'themes.php' ? 'active' : '' ?>">
                        <a href="/admin/themes.php">
                            <i class="bi bi-palette"></i>
                            <span class="text">主题</span>
                        </a>
                    </li>
                    <li class="<?= $request->currentIndex === 'plugins.php' ? 'active' : '' ?>">
                        <a href="/admin/plugins.php">
                            <i class="bi bi-plugin"></i>
                            <span class="text">插件</span>
                        </a>
                    </li>
                    <li class="<?= $request->currentIndex === 'options.php' ? 'active' : '' ?>">
                        <a href="/admin/options.php">
                            <i class="bi bi-gear"></i>
                            <span class="text">设置</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="menu">
                <li class="<?= $request->currentIndex === 'profile.php' ? 'active' : '' ?>">
                    <a href="/admin/profile.php?uid=<?= \Nebula\Widgets\Users\Method::factory()->get('uid') ?>">
                        <!-- <img class="avatar" src="" alt=""> -->
                        <i class="bi bi-person"></i>
                        <span class="text"><?= \Nebula\Widgets\Users\Method::factory()->get('nickname') ?></span>
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
