<?php

/**
 * This file is part of Nebula.
 *
 * (c) 2022 nbacms <nbacms@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Nebula\Widgets;

use Nebula\Common;
use Nebula\Widget;
use Nebula\Plugin as NebulaPlugin;
use Nebula\Helpers\Renderer;
use Nebula\Helpers\Validate;

class Plugin extends Widget
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
        $this->enabledList = NebulaPlugin::export();
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
                $pluginInfo['is_config'] = $pluginInfo['is_activated'] && isset($this->enabledList[$pluginClassName]) && [] !== $this->enabledList[$pluginClassName]['config'];

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

    /**
     * 启用插件
     *
     * @return void
     */
    private function enable()
    {
        $pluginName = $this->getPluginClassName($this->params('pluginName'));

        // 判断组件是否已启用
        if (array_key_exists($pluginName, $this->enabledList)) {
            Notice::factory()->set('不能重复启用插件', 'warning');
            $this->response->redirect('/admin/plugins.php');
        }

        // 判断插件是否存在异常
        if (!class_exists($pluginName) || !method_exists($pluginName, 'activate')) {
            Notice::factory()->set('无法启用插件', 'warning');
            $this->response->redirect('/admin/plugins.php');
        }

        // 获取插件配置
        $renderer = new Renderer();
        call_user_func([$pluginName, 'config'], $renderer);
        $pluginConfig = $renderer->getValues();

        // 获取插件选项
        call_user_func([$pluginName, 'activate']);
        NebulaPlugin::activate($pluginName, $pluginConfig);

        // 提交修改
        $this->db
            ->update('options', ['value' => serialize(NebulaPlugin::export())])
            ->where(['name' => 'plugins'])->execute();

        Notice::factory()->set('启用成功', 'success');
        $this->response->redirect('/admin/plugins.php');
    }

    /**
     * 禁用插件
     *
     * @return void
     */
    private function disabled()
    {
        // 插件类名
        $pluginClassName = $this->getPluginClassName($this->params('pluginName'));

        // 判断组件是否已停用
        if (!isset($this->enabledList[$pluginClassName])) {
            Notice::factory()->set('不能重复停用插件', 'warning');
            $this->response->redirect('/admin/plugins.php');
        }

        // 判断插件是否存在异常
        if (!class_exists($pluginClassName) || !method_exists($pluginClassName, 'activate')) {
            Notice::factory()->set('无法停用插件', 'warning');
            $this->response->redirect('/admin/plugins.php');
        }

        // 获取插件选项
        call_user_func([$pluginClassName, 'deactivate']);
        NebulaPlugin::deactivate($pluginClassName);

        // 提交修改
        $this->db
            ->update('options', ['value' => serialize(NebulaPlugin::export())])
            ->where(['name' => 'plugins'])
            ->execute();

        Notice::factory()->set('禁用成功', 'success');
        $this->response->redirect('/admin/plugins.php');
    }

    /**
     * 更新插件配置
     *
     * @return void
     */
    private function updateConfig()
    {

        // 插件类名
        $pluginClassName = $this->getPluginClassName($this->request->post('pluginName'));

        if (null === $pluginClassName) {
            Notice::factory()->set('插件名不能为空', 'warning');
            $this->response->redirect('/admin/plugins.php');
        }

        if (!array_key_exists($pluginClassName, $this->enabledList)) {
            Notice::factory()->set('插件未启用', 'warning');
            $this->response->redirect('/admin/plugins.php');
        }

        $data = $this->request->post();

        $pluginConfig = $this->enabledList[$pluginClassName]['config'];

        $rules = [];
        foreach (array_keys($pluginConfig) as $value) {
            $rules[$value] = [['type' => 'required', 'message' => '「' . $value . '」不能为空']];
        }
        $validate = new Validate($data, $rules);
        if (!$validate->run()) {
            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/plugin-config.php?name=' . $data['pluginName']);
        }

        // 更新
        NebulaPlugin::updateConfig($pluginClassName, $data);

        // 提交修改
        $this->db
            ->update('options', ['value' => serialize(NebulaPlugin::export())])
            ->where(['name' => 'plugins'])
            ->execute();

        Notice::factory()->set('修改成功', 'success');
        $this->response->redirect('/admin/plugins.php');
    }

    /**
     * 行动方法
     *
     * @return void
     */
    public function action()
    {
        $action = $this->params('action');

        // 启用插件
        $this->on('enable' === $action)->enable();

        // 禁用插件
        $this->on('disabled' === $action)->disabled();

        // 更新插件配置
        $this->on('update-config' === $action)->updateConfig();
    }
}
