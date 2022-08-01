<?php

namespace Nebula\Widgets;

use Nebula\Helpers\Mail;
use Nebula\Helpers\PHPMailer\Exception;
use Nebula\Helpers\Validate;
use Nebula\Widget;

class Option extends Widget
{
    public function execute()
    {
        $options = $this->db->select('options', ['name', 'value']);

        foreach ($options as $option) {
            $this->{$option['name']} = $option['value'];
        }

        $this->smtp = unserialize($this->smtp);
        $this->plugins = unserialize($this->plugins);
    }

    /**
     * 设置配置项
     *
     * @param string $name 配置键
     * @param string $value 配置值
     * @return void
     */
    public function setOption($name, $value)
    {
        if (isset($this->$name)) {
            if ($this->$name !== $value) {
                $this->db->update('options', ['value' => $value], ['name' => $name]);
            }
        } else {
            $this->db->insert('options', ['name' => $name, 'value' => $value]);
        }

        $this->$name = $value;
    }

    /**
     * 删除配置项
     *
     * @param string $name 配置键
     * @return void
     */
    public function deleteOption($name)
    {
        $this->db->delete('options', ['name' => $name]);
    }

    /**
     * 删除多项配置
     *
     * @param array $names 配置键列表
     * @return void
     */
    public function deleteOptions($names)
    {
        foreach ($names as $name) {
            $this->deleteOption($name);
        }
    }

    /**
     * 布尔配置解析
     *
     * @param string $name 配置键
     * @return bool
     */
    public function boolParse($name)
    {
        if (isset($this->$name)) {
            return $this->$name === '1';
        } else {
            return false;
        }
    }

    /**
     * 更新基本配置
     *
     * @return void
     */
    public function updateBasic()
    {
        // 是否是管理员
        if (!User::alloc()->inRole(['0'])) {
            Notice::alloc()->set('非法请求', 'error');
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
            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/options.php');
        }

        // 站点名称
        $this->setOption('title', $data['title'] ?? $this->title);
        // 站点描述
        $this->setOption('description', $data['description'] ?? $this->description);
        // 是否允许注册
        $this->setOption('allowRegister', $data['allowRegister']);

        Notice::alloc()->set('保存成功', 'success');
        $this->response->redirect('/admin/options.php');
    }

    /**
     * 发送测试邮件
     *
     * @return void
     */
    private function sendTestMail()
    {
        // 是否是管理员
        if (!User::alloc()->inRole(['0'])) {
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
        ]);

        if (!$validate->run()) {
            $this->response->sendJSON(['errorCode' => 2, 'type' => 'warning', 'message' => $validate->result[0]['message']]);
        }

        try {
            Mail::getInstance($data['host'], $data['port'], $data['username'], $data['password'], $data['name'])->sendHTML(Option::alloc()->smtp['username'], '测试邮件', '这是一封测试邮件');

            $this->response->sendJSON(['errorCode' => 0, 'type' => 'success', 'message' => '发送成功']);
        } catch (Exception $e) {
            $this->response->sendJSON(['errorCode' => 3, 'type' => 'warning', 'message' => $e->getMessage()]);
        }
    }

    /**
     * 更新 SMTP 配置
     *
     * @return void
     */
    public function updateSMTP()
    {
        // 是否是管理员
        if (!User::alloc()->inRole(['0'])) {
            Notice::alloc()->set('非法请求', 'error');
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
            'name' => [
                ['type' => 'required', 'message' => '名称不能为空'],
            ],
            'username' => [
                ['type' => 'required', 'message' => '用户名不能为空'],
            ],
            'password' => [
                ['type' => 'required', 'message' => '密码不能为空'],
            ],
        ]);

        if (!$validate->run()) {
            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/options.php?action=smtp');
        }

        // 更新
        $this->setOption('smtp', serialize([
            'host' => $data['host'],
            'username' => $data['username'],
            'password' => $data['password'],
            'port' => (int)$data['port'],
            'name' => $data['name'],
        ]));

        Notice::alloc()->set('保存成功', 'success');
        $this->response->redirect('/admin/options.php?action=smtp');
    }

    /**
     * 行动方法
     *
     * @return $this
     */
    public function action()
    {
        $action = $this->params['action'];

        // 更新基本配置
        $this->on($action === 'update-basic')->updateBasic();

        // 发送测试邮件
        $this->on($action === 'send-test-mail')->sendTestMail();

        // 更新 SMTP 配置
        $this->on($action === 'update-smtp')->updateSMTP();

        return $this;
    }

    /**
     * 访问不存在选项返回 null
     *
     * @return string|null
     */
    public function __get($name)
    {
        return $this->$name ?? null;
    }
}
