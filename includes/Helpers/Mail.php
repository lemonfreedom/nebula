<?php

namespace Nebula\Helpers;

use Nebula\Common;
use Nebula\Helpers\PHPMailer\Exception;
use Nebula\Helpers\PHPMailer\PHPMailer;
use Nebula\Response;
use Nebula\Widgets\Options;

class Mail
{
    /**
     * 连接对象
     *
     * @var PHPMailer
     */
    private $mail;

    /**
     * smtp 配置
     *
     * @var array
     */
    private $smtpOption;

    /**
     * 单例实例
     *
     * @var Request
     */
    private static $instance;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        $this->smtpOption = Options::alloc()->smtp;

        // 服务器设置
        $this->mail->isSMTP();
        $this->mail->Host = $this->smtpOption['host'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $this->smtpOption['username'];
        $this->mail->Password = $this->smtpOption['password'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = $this->smtpOption['port'];
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

            // 设置发送人信息
            $this->mail->setFrom($this->smtpOption['username'], $this->smtpOption['name']);
            $this->mail->addAddress($address);
            $this->mail->isHTML(true);
            $this->mail->Subject = Options::alloc()->title . ' 验证码';

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
     * @param string $username 发件人邮箱
     * @param string $name 发件人名称
     * @return void
     */
    public function sendHTML($address, $title, $html, $username = null, $name = null)
    {
        $username = null === $username ? $this->smtpOption['username'] :  $username;
        $name = null === $name ? $this->smtpOption['name'] :  $username;
        // 设置发送人信息
        $this->mail->setFrom($username, $name);

        $this->mail->addAddress($address);
        $this->mail->isHTML(true);
        $this->mail->Subject = $title;

        $this->mail->Body = $html;

        $this->mail->send();
    }

    /**
     * 获取单例实例
     *
     * @return Mail
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
