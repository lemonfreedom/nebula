<?php

namespace Nebula\Helpers;

use PDO;
use PDOException;

class MySQL
{
    /**
     * 单例实例
     *
     * @var MySQL
     */
    private static $instance;

    /**
     * @var PDO
     */
    private $mysql;

    /**
     * @var string
     */
    private $prefix;

    private function __construct()
    {
    }

    public function init($config)
    {
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
     * 获取一行数据
     *
     * @param string $query SQL 语句
     * @param array $params 参数
     * @return array 结果集
     */
    public function getRow($query, $params = [])
    {
        $sth = $this->mysql->prepare($query);
        $sth->execute($params);
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 获取全部数据
     *
     * @param string $query SQL 语句
     * @param array $params 参数
     * @return array 结果集
     */
    public function getRows($query, $params = [])
    {
        $sth = $this->mysql->prepare($query);
        $sth->execute($params);
        return $sth->fetchAll(PDO::FETCH_CLASS);
    }

    /**
     * 获取数据条数
     *
     * @param string $query SQL 语句
     * @return int
     */
    public function getCount($query)
    {
        $sth = $this->mysql->query($query);
        $sth->fetchAll();
    }

    /**
     * 插入数据
     *
     * @param string $table 表名
     * @param array $data 数据
     * @return bool
     */
    public function insert($table, $values)
    {
        if (!isset($values[0])) {
            $values = [$values];
        }

        $columns = array_keys($values[0]);
        $stack = [];
        $params = [];

        foreach ($values as $value) {
            $values = array_map(function () {
                return '?';
            }, $value);

            array_push($stack, '(' . implode(', ', $values) . ')');

            $params = array_merge($params, array_values($value));
        }

        $sth = $this->mysql->prepare("INSERT INTO `{$this->tableParse($table)}` (`" . implode('`, `', $columns) . "`) VALUES " . implode(', ', $stack));
        return $sth->execute($params);
    }

    /**
     * 删除数据
     *
     * @param string $query SQL 语句
     * @param array $params 参数
     * @return bool 操作结果
     */
    public function delete($query, $params = [])
    {
        $sth = $this->mysql->prepare($query);
        return $sth->execute($params);
    }

    /**
     * 删除表
     *
     * @param string $table 表名
     * @return bool
     */
    public function drop($table)
    {
        return false !== $this->mysql->exec("DROP TABLE `{$this->tableParse($table)}`");
    }

    /**
     * 创建表
     *
     * @param string $table 表名
     * @param array $columns 列定义
     * @return bool
     */
    public function create($table, $columns)
    {
        $options = [];
        foreach ($columns as $columnName => $option) {
            array_push($options, "`{$columnName}` " . implode(' ', $option));
        }

        return false !== $this->mysql->exec("CREATE TABLE `{$this->tableParse($table)}` (" . implode(', ', $options) . ')');
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

    /**
     * 获取单例实例
     *
     * @return MySQL
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
