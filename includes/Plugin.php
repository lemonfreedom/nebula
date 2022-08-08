<?php

namespace Nebula;

class Plugin
{
    /**
     * 启用插件列表
     *
     * @var array
     */
    private static $plugins;

    /**
     * 句柄列表
     *
     * @var array
     */
    private static $handles;

    /**
     * 插件实例列表
     *
     * @var array
     */
    private static $instances;

    /**
     * 句柄
     *
     * @var string
     */
    private $handle;

    /**
     * 激活组件缓存
     *
     * @var array
     */
    private static $tmp = [];

    /**
     * @param string $handle 句柄
     * @return void
     */
    public function __construct($handle)
    {
        $this->handle = $handle;
    }

    /**
     * 初始化
     *
     * @param array $plugins 启用插件列表
     * @return void
     */
    public static function init($plugins)
    {
        // 已启用插件列表
        self::$plugins = $plugins;

        self::$handles = array_reduce($plugins, function ($carry, $handles) {
            return array_merge_recursive($carry, $handles['handles']);
        }, []);
    }

    /**
     * 导出启用插件
     *
     * @return array 启用插件信息
     */
    public static function export()
    {
        return self::$plugins;
    }

    /**
     * 获取实例化插件对象
     *
     * @param string $handle 句柄
     * @return Plugin
     */
    public static function factory($handle)
    {
        return self::$instances[$handle] ?? (self::$instances[$handle] = new self($handle));
    }

    /**
     * 启用插件
     *
     * @param string $pluginName 插件名
     * @param array $pluginConfig 插件配置
     * @return void
     */
    public static function activate($pluginName, $pluginConfig)
    {
        self::$plugins[$pluginName]['config'] = $pluginConfig;
        self::$plugins[$pluginName]['handles'] = self::$tmp;
        self::$tmp = [];
    }

    /**
     * 禁用组件
     *
     * @param string $pluginName 插件名
     * @return void
     */
    public static function deactivate($pluginName)
    {
        unset(self::$plugins[$pluginName]);
    }

    /**
     * 更新配置
     *
     * @param string $pluginName 插件名
     * @param array $pluginNewConfig 插件新配置
     * @return void
     */
    public static function updateConfig($pluginName, $pluginNewConfig)
    {
        foreach (array_keys(self::$plugins[$pluginName]['config']) as $value) {
            self::$plugins[$pluginName]['config'][$value] = $pluginNewConfig[$value];
        }
    }

    /**
     * 执行插件方法
     *
     * @param string $name 钩子名
     * @param array $args 参数
     * @return void
     */
    public function __call($name, $args = [])
    {
        // 插件句柄名称
        $pluginHandle = $this->handle . ':' . $name;

        // 如果插件句柄存在执行插件方法
        if (isset(self::$handles[$pluginHandle])) {
            foreach (self::$handles[$pluginHandle] as $callback) {
                call_user_func_array($callback, [array_merge($args[0] ?? [], self::$plugins[explode('::', $callback)[0]]['config'])]);
            }
        }
    }

    /**
     * 设置属性回调
     *
     * @param string $name 钩子名
     * @param string $value 设置属性值
     * @return void
     */
    public function __set($name, $value)
    {
        $name = $this->handle . ':' . $name;
        if (isset(self::$tmp) && isset(self::$tmp[$name])) {
            array_push(self::$tmp[$name], $value);
        } else {
            self::$tmp[$name] = [$value];
        }
    }
}
