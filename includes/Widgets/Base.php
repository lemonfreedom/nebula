<?php

namespace Nebula\Widgets;

use Nebula\Helpers\Medoo;
use Nebula\Widget;

abstract class Base extends Widget
{
    /**
     * 初始化
     */
    public function init()
    {
        // 初始化数据库对象
        $this->db = new Medoo(NEBULA_DB_CONFIG);
    }
}
