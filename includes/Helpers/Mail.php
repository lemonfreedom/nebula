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
        // echo serialize([
        //     'host' => 'smtp.qq.com',
        //     'username' => '226582@qq.com',
        //     'password' => 'revpqsbyoyvucaig',
        //     'port' => 465,
        //     'name' => 'Nebula',
        // ]);
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
     * @param string $code 验证码
     * @return void
     */
    public function sendCaptcha($address, $code)
    {
        try {
            // 设置发送人信息
            $this->mail->setFrom($this->smtpOption['username'], $this->smtpOption['name']);
            $this->mail->addAddress($address);
            $this->mail->isHTML(true);
            $this->mail->Subject = Options::alloc()->title . ' 验证码';
            $this->mail->Body    = '您的验证码是：' . $code;

            $this->mail->send();

            // 将验证码 hash 存入 cookie，将过期时间设置为 10 分钟
            Cookie::factory(time() + 600)->set('code_hash', Common::hash($address . $code));
        } catch (Exception $e) {
            Response::getInstance()->sendJSON(['errorCode' => 1, 'message' => $this->mail->ErrorInfo]);
        }
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
