<?php

namespace Nebula\Helpers;

use Exception;

class SMTP
{
    /**
     * 单例实例
     *
     * @var SMTP
     */
    private static $instance;

    private $username;
    private $connection;
    private $from = '';
    private $address = [];
    private $to = '';
    private $attachment = '';

    private function __construct()
    {
    }

    /**
     * 初始化
     *
     * @param array $options SMTP 选项
     * @return $this
     */
    public function init($options)
    {
        $this->username = $options['username'];

        $connection = @fsockopen($options['host'], $options['port'], $errorCode, $errorMessage, 10);
        @fgets($connection);

        if ($errorCode > 0) {
            throw new Exception($errorMessage);
        }

        @fwrite($connection, 'HELO ' . explode('@', $options['username'])[1] . "\n");
        @fgets($connection);

        @fwrite($connection, 'AUTH LOGIN' . "\n");
        @fgets($connection);

        @fwrite($connection, base64_encode($options['username']) . "\n");
        @fgets($connection);

        @fwrite($connection, base64_encode($options['password']) . "\n");
        @fgets($connection);

        return $this;
    }

    /**
     * 设置发件人
     *
     * @param string $address 发件人地址
     * @param string $name 发件人名称
     * @return $this
     */
    public function setFrom(string $address, string $name = '')
    {
        $this->from .= 'From: ' . $name . '<' . $address . '>' . "\n";

        return $this;
    }

    /**
     * 添加收件人
     *
     * @param string $address 收件人地址
     * @param string $name 收件人名称
     * @return $this
     */
    public function addAddress(string $address, string $name = '')
    {
        array_push($this->address, $address);
        $this->to .= 'To: ' . $name . '<' . $address . '>' . "\n";

        return $this;
    }

    /**
     * 添加附件
     *
     * @param string $path 附件路径
     * @param string $name 附件名称
     * @return $this
     */
    public function addAttachment($path, $name = '')
    {
        if (file_exists($path)) {
            $name = empty($name) ? pathinfo($path)['basename'] : $name;
            $this->attachment .= '--BOUNDARY' . "\n" .
                'Content-Type: application/octet-stream' . "\n" .
                'Content-Disposition: attachment; filename=' . $name . "\n" .
                'Content-Transfer-Encoding: base64' . "\n\n" .
                base64_encode(file_get_contents($path)) . "\n";
        } else {
            throw new Exception('Error: Attachment does not exist');
        }

        return $this;
    }

    /**
     * 发送邮件
     *
     * @param string $subject 标题
     * @param string $body 内容
     * @param string $contentType 内容类型
     * @return void
     */
    public function send($subject, $body, $contentType = 'text/html; charset=utf-8')
    {
        @fwrite($this->connection, 'MAIL FROM: <' . $this->username . '>' . "\n");
        @fgets($this->connection);

        foreach ($this->address as $value) {
            @fwrite($this->connection, 'RCPT TO: <' . $value . '>' . "\n");
            @fgets($this->connection);
        }

        @fwrite($this->connection, 'DATA' . "\n");
        @fgets($this->connection);

        fwrite(
            $this->connection,
            'Subject: ' . $subject . "\n" .
                $this->from .
                $this->to .
                'Content-Type: multipart/mixed; boundary="BOUNDARY"' . "\n\n" .
                '--BOUNDARY' . "\n" .
                'Content-Type: ' . $contentType . "\n\n" .
                $body . "\n" .
                $this->attachment .
                '.' . "\n"
        );
        fgets($this->connection);

        @fwrite($this->connection, 'QUIT' . "\n");
        @fgets($this->connection);
    }

    /**
     * 获取单例实例
     *
     * @return SMTP
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
