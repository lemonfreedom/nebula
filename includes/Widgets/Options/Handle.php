<?php

namespace Nebula\Widgets\Options;

use Nebula\Widget;
use Nebula\Helpers\Validate;
use Nebula\Widgets\Notice;
use Nebula\Widgets\Users\Method as UsersMethod;

class Handle extends Widget
{
    /**
     * 更新基本选项
     *
     * @return void
     */
    public function updateBasic()
    {
        // 是否是管理员
        if (!UsersMethod::factory()->inRole(['0'])) {
            Notice::factory()->set('非法请求', 'error');
            $this->response->redirect('/admin');
        }

        $data = $this->request->post();

        $validate = new Validate($data, [
            'title' => [
                ['type' => 'required', 'message' => '站点名称不能为空'],
            ],
            'description' => [
                ['type' => 'required', 'message' => '站点描述不能为空'],
            ],
            'allowRegister' => [
                ['type' => 'required', 'message' => '是否允许注册不能为空'],
            ],
        ]);

        if (!$validate->run()) {
            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/options.php');
        }

        // 站点名称
        Method::factory()->set('title', $data['title'] ?? $this->title);
        // 站点描述
        Method::factory()->set('description', $data['description'] ?? $this->description);
        // 是否允许注册
        Method::factory()->set('allowRegister', $data['allowRegister']);

        Notice::factory()->set('保存成功', 'success');
        $this->response->redirect('/admin/options.php');
    }

    /**
     * 更新 SMTP 选项
     *
     * @return void
     */
    public function updateSMTP()
    {
        // 是否是管理员
        if (!UsersMethod::factory()->inRole(['0'])) {
            Notice::factory()->set('非法请求', 'error');
            $this->response->redirect('/admin');
        }

        $data = $this->request->post();

        $validate = new Validate($data, [
            'host' => [
                ['type' => 'required', 'message' => '主机名不能为空'],
            ],
            'port' => [
                ['type' => 'required', 'message' => '端口不能为空'],
            ],
            'username' => [
                ['type' => 'required', 'message' => '用户名不能为空'],
            ],
            'password' => [
                ['type' => 'required', 'message' => '密码不能为空'],
            ],
            'name' => [
                ['type' => 'required', 'message' => '发件人名称不能为空'],
            ],
            'email' => [
                ['type' => 'required', 'message' => '发件人邮箱不能为空'],
            ],
        ]);

        if (!$validate->run()) {
            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/options.php?action=smtp');
        }

        // 更新
        Method::factory()->set('smtp', serialize([
            'host' => $data['host'],
            'username' => $data['username'],
            'password' => $data['password'],
            'port' => $data['port'],
            'name' => $data['name'],
            'email' => $data['email'],
        ]));

        Notice::factory()->set('保存成功', 'success');
        $this->response->redirect('/admin/options.php?action=smtp');
    }

    /**
     * 行动方法
     *
     * @return void
     */
    public function action()
    {
        $action = $this->params('action');

        // 更新基本选项
        $this->on($action === 'update-basic')->updateBasic();

        // 更新 SMTP 选项
        $this->on($action === 'update-smtp')->updateSMTP();
    }
}
