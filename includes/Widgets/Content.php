<?php

/**
 * This file is part of Nebula.
 *
 * (c) 2022 nbacms <nbacms@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Nebula\Widgets;

use Nebula\Helpers\Validate;
use Nebula\Widget;

class Content extends Widget
{
    /**
     * 查询文章列表
     *
     * @return void
     */
    public function getPosts()
    {
    }

    /**
     * 创建文章
     *
     * @return void
     */
    private function setPost()
    {
        if (!User::factory()->hasLogin()) {
            $this->response->redirect('/admin/login.php');
        }

        $data = $this->request->post();

        $validate = new Validate($data, [
            'title' => [
                ['type' => 'required', 'message' => '标题不能为空'],
            ],
            'content' => [
                ['type' => 'required', 'message' => '内容不能为空'],
            ],
        ]);
        if (!$validate->run()) {
            Cache::factory()->set('setPostTitle', $this->request->post('title', ''))
                ->set('setPostContent', $this->request->post('content', ''));

            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/post.php');
        }
    }

    /**
     * 行动方法
     *
     * @return void
     */
    public function action()
    {
        $action = $this->params('action');

        // 更新选项
        $this->on('post' === $action)->setPost();
    }
}
