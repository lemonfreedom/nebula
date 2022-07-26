<?php

namespace Nebula\Helpers;

use Nebula\Response;

class Cookie
{
    /**
     * 对象池
     *
     * @var array
     */
    private static $objectPool = [];

    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @var int
     */
    private $expiresOrOptions = 0;

    /**
     * @var string
     */
    private $path = '/';

    /**
     * @var string
     */
    private $domain = '';

    /**
     * @var bool
     */
    private $secure = false;

    /**
     * @var bool
     */
    private $httponly = false;

    /**
     * 构造方法
     *
     * @param int $expiresOrOptions
     * @param string $prefix
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return void
     */
    public function __construct($expiresOrOptions, $prefix, $path, $domain,  $secure, $httponly)
    {
        $this->expiresOrOptions = $expiresOrOptions;
        $this->prefix = $prefix;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httponly = $httponly;
    }

    /**
     * 获取指定的 cookie
     *
     * @param string $name 指定的键
     * @param null|string $default 默认值
     * @return string
     */
    public function get($name, $default = null)
    {
        return $_COOKIE[$this->prefix . $name] ?? $default;
    }

    /**
     * 设置指定的 cookie
     *
     * @param string $name 指定的键
     * @param string $value 设置的值
     * @return $this
     */
    public function set($name, $value)
    {
        $name = $this->prefix . $name;
        $_COOKIE[$name] = $value;
        Response::getInstance()->setCookie($name, $value, $this->expiresOrOptions, $this->path, $this->domain, $this->secure, $this->httponly);

        return $this;
    }

    /**
     * 删除指定的 cookie
     *
     * @param string $name 指定的键
     * @return $this
     */
    public function delete($name)
    {
        $name = $this->prefix . $name;
        Response::getInstance()->setCookie($name, null, -1, $this->path, $this->domain, $this->secure, $this->httponly);
        unset($_COOKIE[$name]);

        return $this;
    }

    /**
     * 工厂方法
     *
     * @param int $expiresOrOptions
     * @param string $prefix
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return Cookie
     */
    public static function factory($expiresOrOptions = 0, $prefix = 'nebula_', $path = '/', $domain = '',  $secure = false, $httponly = false)
    {
        $alias =  $expiresOrOptions . '@' . $prefix . '@' . $path . '@' . $domain . '@' . $secure . '@' . $httponly;

        if (!isset(self::$objectPool[$alias])) {
            self::$objectPool[$alias] = new Cookie($expiresOrOptions, $prefix, $path, $domain,  $secure, $httponly);
        }

        return self::$objectPool[$alias];
    }
}
