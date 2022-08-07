<?php

namespace Nebula\Widgets;

use Nebula\Helpers\Medoo;
use Nebula\Widget;

class Database extends Widget
{
    /**
     * @var Medoo
     */
    public $db;

    public function __construct()
    {
        parent::__construct();

        // 初始化数据库对象
        $this->db = new Medoo(NEBULA_DB_CONFIG);
    }
}
