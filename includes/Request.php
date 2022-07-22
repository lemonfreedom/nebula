<?php

namespace Nebula;

class Request
{
    /**
     * 单例实例
     *
     * @var Request
     */
    private static $instance;

    /**
     * 请求信息
     *
     * @var string
     */
    public $requestURI;

    /**
     * 路径信息，已去除首尾分隔符
     *
     * @var string
     */
    public $pathinfo;

    /**
     * 请求方式
     *
     * @var string
     */
    public $method;

    public function __construct()
    {
        $this->requestURI = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->setPathInfo();
    }

    /**
     * 设置路径信息
     *
     * @return void
     */
    private function setPathInfo()
    {
        $pathinfo = $this->requestURI;

        if ($pos = strpos($pathinfo, '?')) {
            $pathinfo = substr($pathinfo, 0, $pos);
        }

        if ($pos = strpos($pathinfo, '.php')) {
            $pathinfo = substr($pathinfo, $pos + 4);
        }

        $this->pathinfo = trim($pathinfo, '/');
    }

    /**
     * 获取单例实例
     *
     * @return Request
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
