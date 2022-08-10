<?php

namespace Nebula\Widgets;

use Exception;
use Nebula\Widget;
use Nebula\Common;
use Nebula\Response;
use Nebula\Helpers\SMTP;
use Nebula\Helpers\Cookie;
use Nebula\Helpers\Validate;

class Mail extends Widget
{
    /**
     * 连接对象
     *
     * @var SMTP
     */
    private $smtp;

    /**
     * 主机地址
     *
     * @var string
     */
    private $host;

    /**
     * 端口
     *
     * @var string
     */
    private $port;

    /**
     * 用户名
     *
     * @var string
     */
    private $username;

    /**
     * 密码
     *
     * @var string
     */
    private $password;

    /**
     * 发件人名称
     *
     * @var string
     */
    private $name;

    public function init()
    {
        $smtp = Option::factory()->get('smtp');

        $this->host = null === $this->params('host', $smtp['host']);
        $this->port = null === $this->params('port', $smtp['port']);
        $this->username = null === $this->params('username', $smtp['username']);
        $this->password = null === $this->params('password', $smtp['password']);
        $this->name = null === $this->params('name', $smtp['name']);
        $this->email = null === $this->params('email', $smtp['email']);

        $this->smtp = SMTP::getInstance()
            // 初始化
            ->init([
                'host' => $this->host,
                'port' => $this->port,
                'username' => $this->username,
                'password' => $this->password,
            ])
            // 设置发件人
            ->setFrom($this->email, $this->name);
    }

    /**
     * 发生验证码
     *
     * @param string $address 收件人邮箱
     * @return void
     */
    public function sendCaptcha($address)
    {
        try {
            $code = Common::randString(5, true, false);

            $this->smtp
                ->addAddress($address)
                ->send(Option::factory()->get('title') . ' 验证码', '您的验证码是：' . $code);

            // 将验证码 hash 存入 cookie，将过期时间设置为 10 分钟
            Cookie::set('code_hash', Common::hash($address . $code), time() + 600);
        } catch (Exception $e) {
            Response::getInstance()->sendJSON(['errorCode' => 1, 'message' => $e->getMessage()]);
        }
    }

    /**
     * 发送 html
     *
     * @param string $address 收件人邮箱
     * @param string $title 邮件标题
     * @param string $html html 邮件消息
     * @return void
     */
    public function sendHTML($address, $title, $html)
    {
        $this->smtp
            ->addAddress($address)
            ->send($title, $html);
    }

    /**
     * 发送测试邮件
     *
     * @return void
     */
    private function sendTestMail()
    {
        // 是否是管理员
        if (!User::factory()->inRole(['0'])) {
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
            // __CLASS__::factory([
            //     'host' => $data['host'],
            //     'port' => $data['port'],
            //     'username' => $data['username'],
            //     'password' => $data['password'],
            //     'name' => $data['name'],
            //     'email' => $data['email'],
            // ], 'sendTestMail')->sendHTML(User::factory()->get('email'), '测试邮件', '这是一封测试邮件');

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
        $this->on('send-test-mail' === $action)->sendTestMail();
    }
}
