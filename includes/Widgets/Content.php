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
    public function queryContents()
    {
        return $this->db
            ->select('contents', ['cid', 'title', 'content', 'create_time'])
            ->execute();
    }

    /**
     * 创建文章
     *
     * @return void
     */
    private function createContent()
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

        // 插入数据
        $this->db->insert('contents', [
            'title' => $this->request->post('title', ''),
            'content' => $this->request->post('content', ''),
            'create_time' => time(),
        ]);

        Notice::factory()->set('新建成功', 'success');
        $this->response->redirect('/admin/contents.php');
    }

    /**
     * 创建分类
     *
     * @return void
     */
    public function createTerm()
    {
        if (!User::factory()->hasLogin()) {
            $this->response->redirect('/admin/login.php');
        }

        $data = $this->request->post();

        $validate = new Validate($data, [
            'name' => [
                ['type' => 'required', 'message' => '名称不能为空'],
            ],
            'slug' => [
                ['type' => 'required', 'message' => '缩略名不能为空'],
            ],
        ]);
        if (!$validate->run()) {
            Cache::factory()->set('createCategoryName', $this->request->post('name', ''))
                ->set('createCategorySlug', $this->request->post('slug', ''));

            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/create-term.php');
        }

        // 插入数据
        $this->db->insert('terms', [
            'name' => $this->request->post('name', ''),
            'slug' => $this->request->post('slug', ''),
        ]);

        Notice::factory()->set('新建成功', 'success');
        $this->response->redirect('/admin/contents.php?action=terms');
    }

    /**
     * 查询分类列表
     *
     * @return array
     */
    public function queryTerms()
    {
        return $this->db
            ->select('terms', ['tid', 'name', 'slug'])
            ->execute();
    }

    /**
     * 行动方法
     *
     * @return void
     */
    public function action()
    {
        $action = $this->params('action');

        // 创建文章
        $this->on('create-content' === $action)->createContent();

        // 创建分类
        $this->on('create-term' === $action)->createTerm();
    }
}
