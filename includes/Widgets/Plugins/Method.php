<?php

namespace Nebula\Widgets\Plugins;

use Nebula\Common;
use Nebula\Plugin;
use Nebula\Helpers\Renderer;
use Nebula\Widgets\Database;
use Nebula\Widgets\Notice;

class Method extends Database
{
    /**
     * 插件列表
     *
     * @var null|array
     */
    private $pluginList = null;

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
     * 获取插件列表
     *
     * @return array 插件列表
     */
    public function getPluginList()
    {
        if (null === $this->pluginList) {
            // 已启用插件列表
            $plugins = Plugin::export();

            // 插件目录列表
            $pluginDirs = glob(NEBULA_ROOT_PATH . 'content/plugins/*/');

            // 插件列表初始化
            $this->pluginList = array_map(function ($pluginDir) use ($plugins) {
                $pluginInfo = [];

                // 插件目录
                $pluginInfo['dir'] = basename($pluginDir);

                // 插件类名
                $pluginClassName =  $this->getPluginClassName($pluginInfo['dir']);

                // 修改启用状态
                $pluginInfo['is_activated'] = in_array($pluginClassName, array_keys($plugins));

                // 插件是否可配置
                $pluginInfo['is_config'] = $pluginInfo['is_activated'] && [] !== $plugins[$pluginClassName]['config'];

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

        // 已启用插件列表
        $plugins = Plugin::export();

        if (!array_key_exists($pluginClassName, $plugins)) {
            Notice::factory()->set('插件未启用', 'warning');
            $this->response->redirect('/admin/plugins.php');
        }

        // 判断插件是否具备配置功能
        if ([] === $plugins[$pluginClassName]['config']) {
            Notice::factory()->set('配置功能不存在', 'warning');
            $this->response->redirect('/admin/plugins.php');
        }

        $renderer = new Renderer();
        call_user_func([$pluginClassName, 'config'], $renderer);
        $renderer->render($plugins[$pluginClassName]['config']);
    }
}
