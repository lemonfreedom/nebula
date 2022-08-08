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

    /**
     * 当前首页
     *
     * @var string
     */
    public $currentIndex;

    /**
     * @return void
     */
    private function __construct()
    {
        $this->requestURI = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->setPathInfo();
        $scriptFilename = explode('/', $_SERVER['SCRIPT_FILENAME']);
        $this->currentIndex = end($scriptFilename);
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
     * 获取 get 参数
     *
     * @param null|string $name 参数名
     * @param null|string $defaultValue 默认值
     * @return null|string|array
     */
    public function get($name = null, $defaultValue = null)
    {
        if (null === $name) {
            return $_GET;
        } else {
            return $_GET[$name] ?? $defaultValue;
        }
    }

    /**
     * 获取 post 参数
     *
     * @param null|string $name 参数名
     * @param null|string $defaultValue 默认值
     * @return null|string|array
     */
    public function post($name = null, $defaultValue = null)
    {
        if (null === $name) {
            return $_POST;
        } else {
            return $_POST[$name] ?? $defaultValue;
        }
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
