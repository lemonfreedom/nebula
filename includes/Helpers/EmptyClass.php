<?php

namespace Nebula\Helpers;

class EmptyClass
{
    /**
     * 单例实例
     *
     * @var Request
     */
    private static $instance;

    /**
     * 获取单例实例
     *
     * @return Response
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __call($name, $arguments)
    {
    }
}
