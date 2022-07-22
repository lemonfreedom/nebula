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
     * 路径信息
     *
     * @var string
     */
    public $pathinfo;

    public function __construct()
    {
        $this->requestURI = $_SERVER['REQUEST_URI'];
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
            $pathinfo = rtrim(substr($pathinfo, $pos + 4), '/') . '/';
        }

        $this->pathinfo = $pathinfo;
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
