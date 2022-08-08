<?php

namespace Nebula\Widgets;

use Nebula\Common;
use Nebula\Widget;
use Nebula\Helpers\Cookie;
use PDO;

class Cache extends Widget
{
    /**
     * 数据库实例
     *
     * @var MySQL
     */
    private $mysql;

    /**
     * 用户缓存 id
     *
     * @var string
     */
    private $cacheId;

    /**
     * 缓存列表
     *
     * @var array
     */
    private $caches = [];

    public function __construct()
    {
        parent::__construct();

        $this->mysql = MySQL::factory();
    }

    public function init()
    {
        // 删除过期
        $this->mysql
            ->prepare("DELETE FROM {$this->mysql->tableParse('caches')} WHERE expires < :time")
            ->execute(['time' => time()]);

        $this->caches = Common::objectToArray(
            $this->mysql
                ->query("SELECT * FROM {$this->mysql->tableParse('caches')}")
                ->fetchAll(PDO::FETCH_CLASS)
        );

        $cacheId = Cookie::get('cache_id');

        if (null === $cacheId) {
            $cacheId = Common::hash(uniqid());
            Cookie::set('cache_id', $cacheId);
        }

        $this->cacheId = $cacheId;
    }

    /**
     * 获取 caches
     *
     * @param null|string $name 缓存名
     * @param null|string $defaultValue 默认值
     * @return null|string|array
     */
    public function get($name = null, $defaultValue = null)
    {
        if (null === $name) {
            return $this->caches;
        } else {
            $name = $this->cacheId . '@' . $name;
            return array_values(array_filter($this->caches, function ($cache) use ($name) {
                return $cache['name'] === $name;
            }))[0]['value'] ?? $defaultValue;
        }
    }

    /**
     * 设置缓存项
     *
     * @param string $name 缓存名
     * @param string $value 缓存值
     * @param int $expires 过期时间
     * @return void
     */
    public function set($name, $value, $expires = 60)
    {
        $name = $this->cacheId . '@' . $name;

        $index = array_search($name, array_map(function ($cache) {
            return $cache['name'];
        }, $this->caches));

        if (false === $index) {
            $this->mysql->insert('caches', [
                'name' => $name,
                'value' => $value,
                'expires' => time() + $expires
            ]);
            array_push($this->caches, ['name' => $name, 'value' => $value]);
        } else {
            if ($this->caches[$index]['value'] !== $value) {
                $this->mysql->update('caches', [
                    'value' => $value,
                    'expires' => time() + $expires
                ], ['name' => $name]);
                $this->caches[$index]['value'] = $value;
            }
        }
    }

    /**
     * 设置多个缓存项
     *
     * @param array $caches 缓存项列表
     * @param int $expires 过期时间
     * @return void
     */
    public function sets($caches, $expires = 60)
    {
        foreach ($caches as $name => $value) {
            $this->set($name, $value, $expires);
        }
    }
}
