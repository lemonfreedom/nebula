<?php

use Nebula\Helpers\Template;
use Nebula\Widgets\Content;

include __DIR__ . '/modules/common.php'; ?>
<?php $user->hasLogin() || $response->redirect('/admin/login.php'); ?>
<?php include __DIR__ . '/modules/header.php'; ?>
<?php include __DIR__ . '/modules/navbar.php'; ?>
<div class="container">
    <?= \Nebula\Helpers\Template::tabs(
        [
            ['name' => '文章', 'path' => "/admin/contents.php", 'active' => null, 'has' => true],
            ['name' => '分类', 'path' => "/admin/contents.php?action=terms", 'active' => 'terms', 'has' => true],
        ],
        $action,
        \Nebula\Plugin::factory('admin/contents.php')->tab(['action' => $action])
    ) ?>
    <?php if (null === $action) : ?>
        <?php $list = \Nebula\Widgets\Content::factory()->queryContents(); ?>
        <div class="tools">
            <a class="button" href="/admin/create-content.php">发布文章</a>
            <div class="group">
                <div class="button-dropdown">
                    <span>选择项</span>
                    <ul class="dropdown-menu">
                        <li><a id="deleteRows" href="">删除</a></li>
                    </ul>
                </div>
                <form class="group" action="/admin/contents.php" method="GET">
                    <input class="input" type="text" name="keyword" placeholder="输入关键字">
                    <button class="button">搜索</button>
                </form>
            </div>
        </div>
        <div class="table">
            <table>
                <colgroup>
                    <col width="10%">
                    <col width="30%">
                    <col width="20%">
                    <col width="20%">
                    <col width="20%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>标题</th>
                        <th>分类</th>
                        <th>作者</th>
                        <th>发布日期</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $item) : ?>
                        <tr>
                            <td>
                                <label class="checkbox">
                                    <input type="checkbox" value="<?= $item['cid'] ?>">
                                    <div class="checkmark"></div>
                                </label>
                            </td>
                            <td><a href="" title=""><?= $item['title'] ?></a></td>
                            <td><?= $item['term_name'] ?></td>
                            <td>张三</td>
                            <td><?= $item['create_time'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= Template::pagination('/admin/contents.php', [], $request->get('page', 1), ceil(Content::factory()->queryContentCount() / 10)) ?>
    <?php elseif ('terms' === $action) : ?>
        <?php $list = \Nebula\Widgets\Content::factory()->queryTerms(); ?>
        <div class="tools">
            <a class="button" href="/admin/create-term.php">新建分类</a>
            <div class="button-dropdown">
                <span>选择项</span>
                <ul class="dropdown-menu">
                    <li><a href="">删除</a></li>
                </ul>
            </div>
        </div>
        <div class="table">
            <table>
                <colgroup>
                    <col width="10%">
                    <col width="30%">
                    <col width="60%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>名称</th>
                        <th>缩略名</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $item) : ?>
                        <tr>
                            <td>
                                <label class="checkbox">
                                    <input type="checkbox">
                                    <div class="checkmark"></div>
                                </label>
                            </td>
                            <td><?= $item['name'] ?></td>
                            <td><?= $item['slug'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    <?php \Nebula\Plugin::factory('admin/options.php')->tabContent(['action' => $action]); ?>
</div>
<?php include __DIR__ . '/modules/copyright.php'; ?>
<?php include __DIR__ . '/modules/common-js.php'; ?>
<?php include __DIR__ . '/modules/footer.php'; ?>
