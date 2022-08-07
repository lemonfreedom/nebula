<?php

namespace Nebula\Widgets;

use Nebula\Helpers\Mail;
use Nebula\Helpers\PHPMailer\Exception;
use Nebula\Helpers\Validate;

class Option extends Base
{
    /**
     * @var array
     */
    private $options = [];

    public function execute()
    {
        $this->options = $this->db->select('options', ['name', 'value']);

        foreach ($this->options as $index => $option) {
            // 布尔选项处理
            if ('allowRegister' === $option['name']) {
                $this->options[$index]['value'] = '1' === $this->options[$index]['value'];
            }

            // 选项去序列化处理
            if (in_array($option['name'], ['smtp', 'plugins', 'theme'])) {
                $this->options[$index]['value'] = unserialize($this->options[$index]['value']);
            }
        }
    }

    /**
     * 获取选项
     *
     * @param null|string $name 选项名
     * @param null|string $defaultValue 默认值
     * @return null|string|array
     */
    public function get($name = null, $defaultValue = null)
    {
        if (null === $name) {
            return $this->options;
        } else {
            return array_values(array_filter($this->options, function ($option) use ($name) {
                return $option['name'] === $name;
            }))[0]['value'] ?? $defaultValue;
        }
    }

    /**
     * 设置配置项
     *
     * @param string $name 选项名
     * @param string $value 选项值
     * @return void
     */
    public function set($name, $value)
    {
        $name = $this->cacheId . '@' . $name;

        $index = array_search($name, array_map(function ($option) {
            return $option['name'];
        }, $this->options));

        if (false === $index) {
            $this->db->insert('options', [
                'name' => $name,
                'value' => $value,
            ]);
            array_push($this->options, ['name' => $name, 'value' => $value]);
        } else {
            if ($this->options[$index]['value'] !== $value) {
                $this->db->update('options', [
                    'value' => $value,
                ], ['name' => $name]);
                $this->options[$index]['value'] = $value;
            }
        }
    }


    /**
     * 设置多个选项项
     *
     * @param array $options 选项列表
     * @return void
     */
    public function sets($options)
    {
        foreach ($options as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * 删除选项
     *
     * @param string $name 选项名称
     * @return void
     */
    public function deleteOption($name)
    {
        $this->db->delete('options', ['name' => $name]);
    }

    /**
     * 删除多项选项
     *
     * @param array $names 选项名称列表
     * @return void
     */
    public function deleteOptions($names)
    {
        foreach ($names as $name) {
            $this->deleteOption($name);
        }
    }

    /**
     * 更新基本选项
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
        $this->set('title', $data['title'] ?? $this->title);
        // 站点描述
        $this->set('description', $data['description'] ?? $this->description);
        // 是否允许注册
        $this->set('allowRegister', $data['allowRegister']);

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
            'email' => [
                ['type' => 'required', 'message' => '发件人邮箱不能为空'],
            ],
        ]);

        if (!$validate->run()) {
            $this->response->sendJSON(['errorCode' => 2, 'type' => 'warning', 'message' => $validate->result[0]['message']]);
        }

        try {
            Mail::getInstance($data['host'], $data['port'], $data['username'], $data['password'], $data['name'], $data['email'])->sendHTML(User::alloc()->get('email'), '测试邮件', '这是一封测试邮件');

            $this->response->sendJSON(['errorCode' => 0, 'type' => 'success', 'message' => '发送成功']);
        } catch (Exception $e) {
            $this->response->sendJSON(['errorCode' => 3, 'type' => 'warning', 'message' => $e->getMessage()]);
        }
    }

    /**
     * 更新 SMTP 选项
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
            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/options.php?action=smtp');
        }

        // 更新
        $this->set('smtp', serialize([
            'host' => $data['host'],
            'username' => $data['username'],
            'password' => $data['password'],
            'port' => $data['port'],
            'name' => $data['name'],
            'email' => $data['email'],
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

        // 更新基本选项
        $this->on($action === 'update-basic')->updateBasic();

        // 发送测试邮件
        $this->on($action === 'send-test-mail')->sendTestMail();

        // 更新 SMTP 选项
        $this->on($action === 'update-smtp')->updateSMTP();

        return $this;
    }
}
