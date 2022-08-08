<?php

namespace Nebula\Widgets\Mails;

use Exception;
use Nebula\Widget;
use Nebula\Helpers\Validate;
use Nebula\Widgets\Users\Method as UsersMethod;

class Handle extends Widget
{
    /**
     * 发送测试邮件
     *
     * @return void
     */
    private function sendTestMail()
    {
        // 是否是管理员
        if (!UsersMethod::factory()->inRole(['0'])) {
            $this->response->sendJSON(['errorCode' => 1, 'type' => 'error', 'message' => '非法请求']);
        }

        $data = $this->request->post();

        $validate = new Validate($data, [
            'host' => [
                ['type' => 'required', 'message' => '主机名不能为空'],
            ],
            'port' => [
                ['type' => 'required', 'message' => '端口不能为空'],
            ],
            'name' => [
                ['type' => 'required', 'message' => '名称不能为空'],
            ],
            'username' => [
                ['type' => 'required', 'message' => '用户名不能为空'],
            ],
            'password' => [
                ['type' => 'required', 'message' => '密码不能为空'],
            ],
            'email' => [
                ['type' => 'required', 'message' => '发件人邮箱不能为空'],
            ],
        ]);

        if (!$validate->run()) {
            $this->response->sendJSON(['errorCode' => 2, 'type' => 'warning', 'message' => $validate->result[0]['message']]);
        }

        try {
            Method::factory([
                'host' => $data['host'],
                'port' => $data['port'],
                'username' => $data['username'],
                'password' => $data['password'],
                'name' => $data['name'],
                'email' => $data['email'],
            ])->sendHTML(UsersMethod::factory()->get('email'), '测试邮件', '这是一封测试邮件');

            $this->response->sendJSON(['errorCode' => 0, 'type' => 'success', 'message' => '发送成功']);
        } catch (Exception $e) {
            $this->response->sendJSON(['errorCode' => 3, 'type' => 'warning', 'message' => $e->getMessage()]);
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

        // 发送测试邮件
        $this->on($action === 'send-test-mail')->sendTestMail();
    }
}
