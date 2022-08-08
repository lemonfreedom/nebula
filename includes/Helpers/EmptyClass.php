<?php

namespace Nebula\Helpers;

class EmptyClass
{
    /**
     * 单例实例
     *
     * @var EmptyClass
     */
    private static $instance;

    /**
     * 获取单例实例
     *
     * @return EmptyClass
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call($name, $arguments)
    {
    }
}
