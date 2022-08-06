<?php

namespace Nebula\Helpers;

use Nebula\Response;

class Cookie
{
    /**
     * @var string
     */
    private static $prefix = 'nebula_';

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
     * @param null|string $defaultValue 默认值
     * @return string
     */
    public static function get($name, $defaultValue = null)
    {
        return $_COOKIE[self::$prefix . $name] ?? $defaultValue;
    }

    /**
     * 设置指定的 cookie
     *
     * @param string $name 指定的键
     * @param string $value 设置的值
     * @param string $expiresOrOptions 过期时间
     * @return void
     */
    public static function set($name, $value, $expiresOrOptions = 0)
    {
        $name = self::$prefix . $name;
        $_COOKIE[$name] = $value;
        Response::getInstance()->setCookie($name, $value, $expiresOrOptions, self::$path, self::$domain, self::$secure, self::$httponly);
    }

    /**
     * 删除指定的 cookie
     *
     * @param string $name 指定的键
     * @return void
     */
    public static function delete($name)
    {
        $name = self::$prefix . $name;
        Response::getInstance()->setCookie($name, null, -1, self::$path, self::$domain, self::$secure, self::$httponly);
        unset($_COOKIE[$name]);
    }
}
