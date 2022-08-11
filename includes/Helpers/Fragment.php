<?php

/**
 * This file is part of Nebula.
 *
 * (c) 2022 nbacms <nbacms@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Nebula\Helpers;

class Fragment
{
    /**
     * 单例实例
     *
     * @var Fragment
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
     * @return Fragment
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
