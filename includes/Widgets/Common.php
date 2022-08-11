<?php

/**
 * This file is part of Nebula.
 *
 * (c) 2022 Noah Zhang <nbacms@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Nebula\Widgets;

use Nebula\Plugin;
use Nebula\Widget;

class Common extends Widget
{
    /**
     * 行动方法
     *
     * @return void
     */
    public function action()
    {
        // 注册公共行动插件，主要用于插件
        Plugin::factory('includes/Widgets/Common.php')->action($this->params());
    }
}
