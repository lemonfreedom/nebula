<?php

namespace Nebula\Helpers;

use Nebula\Common;
use Nebula\Helpers\PHPMailer\Exception;
use Nebula\Helpers\PHPMailer\PHPMailer;
use Nebula\Response;
use Nebula\Widgets\Option;

class Mail
{
    /**
     * 连接对象
     *
     * @var PHPMailer
     */
    private $mail;

    /**
     * 单例实例
     *
     * @var Request
     */
    private static $instance;

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
     * @return void
     */
    public function __construct($host = null, $port = null, $username = null, $password = null, $name = null)
    {
        $smtpOption = Option::alloc()->smtp;

        $this->host = null === $host ? $smtpOption['host'] : $host;
        $this->port = null === $port ? $smtpOption['port'] : $port;
        $this->username = null === $username ? $smtpOption['username'] : $username;
        $this->password = null === $password ? $smtpOption['password'] : $password;
        $this->name = null === $name ? $smtpOption['name'] : $name;

        // 初始化
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = $this->host;
        $this->mail->Port = $this->port;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $this->username;
        $this->mail->Password = $this->password;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
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
            $this->mail->setFrom($this->username, $this->name);
            $this->mail->addAddress($address);
            $this->mail->isHTML(true);
            $this->mail->Subject = Option::alloc()->title . ' 验证码';

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
        $username = null === $username ? $this->username :  $username;
        $name = null === $name ? $this->name :  $username;
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
     * @param null|string $host
     * @param null|int $port
     * @param null|string $username
     * @param null|string $password
     * @param null|string $name
     * @return Mail
     */
    public static function getInstance($host = null, $port = null, $username = null, $password = null, $name = null)
    {
        if (!isset(self::$instance)) {
            self::$instance = new self($host, $port, $username, $password, $name);
        }

        return self::$instance;
    }
}
