<?php

namespace Nebula\Widgets;

use PDO;
use PDOException;
use Nebula\Widget;

class MySQL extends Widget
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var PDO
     */
    private $mysql;

    public function init()
    {
        $config = $this->params();

        try {
            $this->mysql = new PDO("mysql:dbname={$config['dbname']};host={$config['host']};port={$config['port']}", $config['username'], $config['password'], [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
        } catch (PDOException $e) {
            throw $e;
        }

        $this->prefix = $config['prefix'];
    }

    /**
     * 表名格式化
     *
     * @param string $table 表名
     * @return string
     */
    public function tableParse($table)
    {
        return $this->prefix . $table;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this, $name], $arguments);
        } else {
            return call_user_func_array([$this->mysql, $name], $arguments);
        }
    }
}
