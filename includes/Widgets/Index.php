<?php

namespace Nebula\Widgets;

use Nebula\Widget;

class Index extends Widget
{
    public function render()
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
        print_r($this->params);
        $this->on(true)->render();
    }
}
