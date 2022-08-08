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

    private function __construct()
    {
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public function __call($name, $arguments)
    {
    }

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
}
