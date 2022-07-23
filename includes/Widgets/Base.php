<?php

namespace Nebula\Widgets;

use Nebula\Helpers\Medoo;
use Nebula\Widget;

abstract class Base extends Widget
{
    /**
     * 数据库对象
     *
     * @var Medoo
     */
    protected $db;

    /**
     * 初始化方法
     *
     * @return void
     */
    protected function init()
    {
        // 初始化数据库对象
        $this->db = new Medoo(NEBULA_DB_CONFIG);
    }
}
