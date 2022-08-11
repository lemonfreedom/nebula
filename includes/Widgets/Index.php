<?php

/**
 * This file is part of Nebula.
 *
 * (c) 2022 NoahZhang <nbacms@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

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
