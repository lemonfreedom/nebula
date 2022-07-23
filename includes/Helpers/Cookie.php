<?php

namespace Nebula\Helpers;

use Nebula\Response;

class Cookie
{
    /**
     * @var string
     */
    private static $prefix = '';

    /**
     * @var int
     */
    private static $expiresOrOptions = 0;

    /**
     * @var string
     */
    private static $path = '/';

    /**
     * @var string
     */
    private static $domain = '';

    /**
     * @var bool
     */
    private static $secure = false;

    /**
     * @var bool
     */
    private static $httponly = false;

    /**
     * 获取指定的 cookie
     *
     * @param string $name 指定的键
     * @param null|string $default 默认值
     */
    public static function get($name, $default = null)
    {
        return $_COOKIE[self::$prefix . $name] ?? $default;
    }

    /**
     * 设置指定的 cookie
     *
     * @param string $name 指定的键
     * @param string $value 设置的值
     */
    public static function set($name, $value)
    {
        $name = self::$prefix . $name;
        $_COOKIE[$name] = $value;
        Response::getInstance()->setCookie($name, $value, self::$expiresOrOptions, self::$path, self::$domain, self::$secure, self::$httponly);
    }

    /**
     * 删除指定的 cookie
     *
     * @param string $name 指定的键
     */
    public static function delete($name)
    {
        $name = self::$prefix . $name;
        Response::getInstance()->setCookie($name, null, -1, self::$path, self::$domain, self::$secure, self::$httponly);
        unset($_COOKIE[$name]);
    }
}
