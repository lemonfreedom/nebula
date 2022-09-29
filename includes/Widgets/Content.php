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
use Nebula\Libraries\Michelf\Markdown;
use Nebula\Libraries\Michelf\MarkdownExtra;
use Nebula\Widget;

class Content extends Widget
{
    /**
     * 渲染文章详情页
     *
     * @return void
     */
    public function article()
    {
        $data = $this->queryContentById($this->params('id'));

        $data['parse_content'] = Markdown::defaultTransform($data['content']);

        $this->response->render('article', $data);
    }

    /**
     * 分页查询文章列表
     *
     * @return void
     */
    public function queryContents()
    {
        $page = $this->request->get('page', '1');
        $keyword = '%' . trim($this->request->get('keyword', '')) . '%';

        $result = $this->db
            ->select('contents', ['cid', 'title', 'content', 'create_time', 'terms.name[term_name]'])
            ->join('terms', ['contents.tid' => 'terms.tid'], 'LEFT JOIN')
            ->where([
                'OR' => [
                    'cid[LIKE]' => $keyword,
                    'title[LIKE]' => $keyword,
                    'content[LIKE]' => $keyword,
                    'terms.name[LIKE]' => $keyword,
                ],
            ])
            ->order(['create_time'])
            ->limit(($page - 1) * 10, 10)
            ->execute();

        array_walk($result, function (&$item) {
            $item['create_time'] = date('Y-m-d H:i:s', $item['create_time']);
        });

        return $result;
    }

    /**
     * 通过文章 ID 获取文章详情
     *
     * @return void
     */
    public function queryContentById($cid)
    {
        $data = $this->db
            ->get('contents', ['cid', 'title', 'content', 'create_time', 'terms.name[term_name]'])
            ->join('terms', ['contents.tid' => 'terms.tid'], 'LEFT JOIN')
            ->where(['cid' => $cid])
            ->execute();

        $data['create_time'] = date('Y-m-d H:i:s', $data['create_time']);

        return $data;
    }

    /**
     * 查询文章条数
     *
     * @return void
     */
    public function queryContentCount()
    {
        return $this->db
            ->count('contents')
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
            'tid' => [
                ['type' => 'required', 'message' => '分类不能为空'],
            ],
            'title' => [
                ['type' => 'required', 'message' => '标题不能为空'],
            ],
            'content' => [
                ['type' => 'required', 'message' => '内容不能为空'],
            ],
        ]);
        if (!$validate->run()) {
            Cache::factory()->set('setPostTitle', $this->request->post('title', ''))
                ->set('setPostContent', $this->request->post('content', ''))
                ->set('setPostTid', $this->request->post('tid', ''));

            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/create-content.php');
        }

        // 插入数据
        $this->db->insert('contents', [
            'tid' => $this->request->post('tid', ''),
            'title' => $this->request->post('title', ''),
            'content' => $this->request->post('content', ''),
            'create_time' => time(),
        ]);

        Notice::factory()->set('新建成功', 'success');
        Cache::factory()->clean();
        $this->response->redirect('/admin/contents.php');
    }

    /**
     * 删除文章
     *
     * @return void
     */
    public function deleteContent()
    {
        if (!User::factory()->hasLogin()) {
            $this->response->redirect('/admin/login.php');
        }

        $data = $this->request->post();

        $validate = new Validate($data, [
            'id' => [
                ['type' => 'required', 'message' => '未选择删除项'],
            ],
        ]);
        if (!$validate->run()) {
            $this->response->sendJSON(null, 1, $validate->result[0]['message']);
        }

        $ids = explode(',', $data['id']);

        // 删除
        $this->db->delete('contents')
            ->where(['cid[IN]' => $ids])
            ->execute();

        Notice::factory()->set('删除成功', 'success');
        $this->response->sendJSON(['redirect' => '/admin/contents.php']);
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
     * 创建分类
     *
     * @return void
     */
    private function createTerm()
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
     * 行动方法
     *
     * @return void
     */
    public function action()
    {
        $action = $this->params('action');

        // 创建文章
        $this->on('create-content' === $action)->createContent();

        // 删除文章
        $this->on('delete-content' === $action)->deleteContent();

        // 创建分类
        $this->on('create-term' === $action)->createTerm();
    }
}
