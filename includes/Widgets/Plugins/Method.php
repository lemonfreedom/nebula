<?php

namespace Nebula\Widgets\Plugins;

use Nebula\Common;
use Nebula\Widget;
use Nebula\Plugin;
use Nebula\Helpers\Renderer;
use Nebula\Widgets\Notice;

class Method extends Widget
{
    /**
     * 已启用列表
     *
     * @var array
     */
    private $enabledList = [];

    /**
     * 插件列表
     *
     * @var null|array
     */
    private $pluginList = null;

    public function init()
    {
        $this->enabledList = Plugin::export();
    }

    /**
     * 获取插件类名
     *
     * @param string 插件名
     * @return string 类名
     */
    public function getPluginClassName($pluginName)
    {
        return 'Content\Plugins\\' . $pluginName . '\Main';
    }

    /**
     * 获取插件信息
     *
     * @param null|string $name 字段名
     * @param string $defaultValue 默认值
     * @return null|string|array
     */
    public function get($name = null, $defaultValue = '')
    {
        $pluginName = $this->params('pluginName');

        $pluginList = $this->getPluginList();

        if (null === $pluginName) {
            return $pluginList;
        } else {
            $pluginInfo = array_values(array_filter($pluginList, function ($plugin) use ($pluginName) {
                return $plugin['dir'] === $pluginName;
            }))[0];

            if (null === $name) {
                return $pluginInfo;
            } else {
                return $pluginInfo[$name] ?? $defaultValue;
            }
        }
    }

    /**
     * 获取插件列表
     *
     * @return array 插件列表
     */
    public function getPluginList()
    {
        if (null === $this->pluginList) {
            // 插件目录列表
            $pluginDirs = glob(NEBULA_ROOT_PATH . 'content/plugins/*/');

            // 插件列表初始化
            $this->pluginList = array_map(function ($pluginDir) {
                $pluginInfo = [];

                // 插件目录
                $pluginInfo['dir'] = basename($pluginDir);

                // 插件类名
                $pluginClassName =  $this->getPluginClassName($pluginInfo['dir']);

                // 修改启用状态
                $pluginInfo['is_activated'] = in_array($pluginClassName, array_keys($this->enabledList));

                // 插件是否可配置
                $pluginInfo['is_config'] = $pluginInfo['is_activated'] && [] !== $this->enabledList[$pluginClassName]['config'];

                $pluginIndexPath = $pluginDir . '/Main.php';

                // 判断插件是否完整
                $pluginInfo['is_complete'] = file_exists($pluginIndexPath);

                return array_merge($pluginInfo, Common::parseDoc($pluginIndexPath));
            }, $pluginDirs);
        }

        return $this->pluginList;
    }

    /**
     * 插件配置
     */
    public function config()
    {
        // 插件类名
        $pluginClassName = $this->getPluginClassName($this->params('pluginName'));

        if (!array_key_exists($pluginClassName, $this->enabledList)) {
            Notice::factory()->set('插件未启用', 'warning');
            $this->response->redirect('/admin/plugins.php');
        }

        // 判断插件是否具备配置功能
        if ([] === $this->enabledList[$pluginClassName]['config']) {
            Notice::factory()->set('配置功能不存在', 'warning');
            $this->response->redirect('/admin/plugins.php');
        }

        $renderer = new Renderer();
        call_user_func([$pluginClassName, 'config'], $renderer);
        $renderer->render($this->enabledList[$pluginClassName]['config']);
    }
}
