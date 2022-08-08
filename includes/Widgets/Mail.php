<?php

namespace Nebula\Widgets;

use Nebula\Common;
use Nebula\Helpers\Cookie;
use Nebula\Helpers\PHPMailer\Exception;
use Nebula\Helpers\PHPMailer\PHPMailer;
use Nebula\Response;
use Nebula\Widget;
use Nebula\Widgets\Option;

class Mail extends Widget
{
    /**
     * 连接对象
     *
     * @var PHPMailer
     */
    private $mail;

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

    /**
     * 构造函数
     *
     * @param null|string $host
     * @param null|int $port
     * @param null|string $username
     * @param null|string $password
     * @param null|string $name
     * @param null|string $email
     * @return void
     */
    public function execute()
    {
        $smtp = Option::factory()->get('smtp');

        $this->host = null === $this->params('host', $smtp['host']);
        $this->port = null === $this->params('port', $smtp['port']);
        $this->username = null === $this->params('username', $smtp['username']);
        $this->password = null === $this->params('password', $smtp['password']);
        $this->name = null === $this->params('name', $smtp['name']);
        $this->email = null === $this->params('email', $smtp['email']);

        // 初始化
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = $this->host;
        $this->mail->Port = $this->port;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $this->username;
        $this->mail->Password = $this->password;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->CharSet = 'UTF-8';

        // 设置发送人信息
        $this->mail->setFrom($this->email, $this->name);
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
            $code = Common::randString(5);


            $this->mail->addAddress($address);
            $this->mail->isHTML(true);
            $this->mail->Subject = Option::factory()->get('title') . ' 验证码';

            $this->mail->Body = '您的验证码是：' . $code;

            $this->mail->send();

            // 将验证码 hash 存入 cookie，将过期时间设置为 10 分钟
            Cookie::set('code_hash', Common::hash($address . $code), time() + 600);
        } catch (Exception $e) {
            Response::getInstance()->sendJSON(['errorCode' => 1, 'message' => $this->mail->ErrorInfo]);
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
        $this->mail->addAddress($address);
        $this->mail->isHTML(true);
        $this->mail->Subject = $title;

        $this->mail->Body = $html;

        $this->mail->send();
    }
}
