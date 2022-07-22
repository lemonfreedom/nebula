<?php

namespace Nebula\Widgets;

use Nebula\Widget;

class User extends Widget
{
    public function login()
    {
        echo 'render';
    }

    /**
     * 监听方法
     *
     * @var void
     */
    public function action()
    {
        $this->on(true)->login();
    }
}
