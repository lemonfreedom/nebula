<?php

namespace Nebula\Widgets;

use Nebula\Helpers\Cookie;
use Nebula\Helpers\Validate;

class Post extends Base
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
            return $this->db->get('posts', ['pid', 'tid', 'title', 'content'], [
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
        return $this->db->select('posts', ['pid', 'tid', 'title'], [
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

        $validate = new Validate($data, [
            'title' => [
                ['type' => 'required', 'message' => '标题不能为空'],
            ],
            'tid' => [
                ['type' => 'required', 'message' => '分类不能为空'],
            ],
            'content' => [
                ['type' => 'required', 'message' => '内容不能为空'],
            ],
        ]);
        // 表单验证
        if (!$validate->run()) {
            Cache::alloc()->set('createPostTitle', $this->request->post('title', ''));
            Cache::alloc()->set('createPostTid', $this->request->post('tid', ''));
            Cache::alloc()->set('createPostContent', $this->request->post('content', ''));

            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/create-post.php');
        }

        if ($data['content'] === '{"ops":[{"insert":"\n"}]}') {
            Cache::alloc()->set('createPostTitle', $this->request->post('title', ''));
            Cache::alloc()->set('createPostTid', $this->request->post('tid', ''));

            Notice::alloc()->set('内容不能为空', 'warning');
            $this->response->redirect('/admin/create-post.php');
        }

        $this->db->insert('posts', [
            'tid' =>  $data['tid'],
            'title' =>  $data['title'],
            'content' =>  $data['content'],
        ]);

        Notice::alloc()->set('新增成功', 'success');
        $this->response->redirect('/admin/contents.php');
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
