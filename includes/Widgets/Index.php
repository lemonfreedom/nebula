<?php

namespace Nebula\Widgets;

use Nebula\Widget;

class Index extends Widget
{
    /**
     * 首页渲染函数
     *
     * @return void
     */
    public function render()
    {
        $this->response->render('index');
    }
}
