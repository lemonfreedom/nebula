<?php

namespace Nebula;

use Nebula\Helpers\EmptyClass;

class Widget
{
    /**
     * widget 对象池
     *
     * @var array
     */
    private static $widgetPool = [];

    /**
     * 请求对象
     *
     * @var Request
     */
    protected $request;

    /**
     * 响应对象
     *
     * @var Response
     */
    protected $response;

    /**
     * 参数
     *
     * @var array
     */
    protected $params;

    /**
     * 构造函数
     *
     * @param Request $request Request 对象
     * @param Response $response Response 对象
     * @param array $params 参数
     * @return void
     */
    public function __construct($params)
    {
        $this->request = Request::getInstance();
        $this->response = Response::getInstance();
        $this->params = $params;

        // 执行初始化方法
        $this->init();
    }

    /**
     * 获取组件实例
     *
     * @param array $params 参数
     * @return object 组件实例
     */
    public static function alloc($params = [])
    {
        return self::factory(static::class, $params);
    }

    /**
     * 重新执行实例并写入别名实例
     *
     * @param string $alias 别名
     * @param array $params 参数
     * @return object 组件实例
     */
    public static function allocWithAlias($alias, $params = [])
    {
        return self::factory(static::class . '@' . $alias, $params);
    }

    /**
     * 工厂方法
     *
     * @param string $alias 别名
     * @param array $params 参数
     * @return object 组件实例
     */
    public static function factory($alias, $params = [])
    {
        [$className] = explode('@', $alias);

        // 判断组件池是否存在当前组件
        if (!isset(self::$widgetPool[$alias])) {
            try {
                $widget = new $className($params);
                $widget->execute();
            } catch (\Throwable $th) {
                $widget = $widget ?? null;
                throw $th;
            }

            self::$widgetPool[$alias] = $widget;
        }

        return self::$widgetPool[$alias];
    }

    /**
     * 行动绑定
     *
     * @param boolean $condition
     * @return this
     */
    public function on($condition)
    {
        if ($condition) {
            return $this;
        } else {
            return EmptyClass::getInstance();
        }
    }

    /**
     * 初始化方法
     *
     * @return void
     */
    protected function init()
    {
    }

    /**
     * 执行方法
     *
     * @return void
     */
    protected function execute()
    {
    }
}
