<?php

namespace Nebula\Widgets;

use Nebula\Widget;

class Post extends Widget
{
    /**
     * 获取指定文章信息
     *
     * @return array
     */
    public function get()
    {
        $pid = $this->params['pid'] ?? null;

        if (null !== $pid) {
            return $this->db->get('posts', ['pid', 'mid', 'title', 'content'], [
                'pid' => $pid,
            ]);
        } else {
            return [];
        }
    }

    /**
     * 获取文章列表
     *
     * @return array 文章列表
     */
    public function getPostList()
    {
        // $keyword = trim($this->params['keyword']);
        return $this->db->select('posts', ['pid', 'mid', 'title', 'content'], [
            // 'OR' => [
            //     'uid[~]' => $keyword,
            //     'username[~]' => $keyword,
            //     'email[~]' => $keyword,
            //     'nickname[~]' => $keyword,
            // ],
        ]);
    }

    /**
     * 创建文章
     *
     * @return void
     */
    public function createPost()
    {
        $data = $this->request->post();

        $this->db->insert('posts', [
            'mid' =>  $data['mid'],
            'title' =>  $data['title'],
            'content' =>  $data['content'],
        ]);

        Notice::alloc()->set('新增成功', 'success');
        $this->response->redirect('/admin/posts.php');
    }

    /**
     * 行动方法
     *
     * @return $this
     */
    public function action()
    {
        $action = $this->params['action'];

        // 发送测试邮件
        $this->on($action === 'create-post')->createPost();

        return $this;
    }
}
