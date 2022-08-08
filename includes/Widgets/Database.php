<?php

namespace Nebula\Widgets;

use Nebula\Widget;
use Nebula\Helpers\Medoo;

class Database extends Widget
{
    /**
     * @var Medoo
     */
    public $db;

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // 初始化数据库对象
        $this->db = new Medoo(NEBULA_DB_CONFIG);
    }
}
