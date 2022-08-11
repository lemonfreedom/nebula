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
use Nebula\Helpers\Renderer;
use Nebula\Helpers\Validate;
use Nebula\Widgets\Option;

class Theme extends Widget
{
    /**
     * 已启用列表
     *
     * @var array
     */
    private $enabled = [];

    /**
     * 插件列表
     *
     * @var null|array
     */
    private $themeList = null;

    public function init()
    {
        $this->enabled = Option::factory()->get('theme');
    }

    /**
     * 获取主题信息
     *
     * @param null|string $name 字段名
     * @return mixed
     */
    public function get($name = null)
    {
        if (null === $name) {
            return $this->enabled;
        } else {
            return $this->enabled[$name];
        }
    }

    /**
     * 获取主题列表
     *
     * @return array 主题列表
     */
    public function getThemeList()
    {
        if (null === $this->themeList) {
            // 主题目录列表
            $themeDirs = glob(NEBULA_ROOT_PATH . 'content/themes/*/');

            // 主题信息
            $this->themeList = array_map(function ($themeDirs) {
                $themeInfo = [];
                // 主题目录
                $themeInfo['dir'] = basename($themeDirs);

                $themeInfo['screenshot_url'] = '/content/themes/' . $themeInfo['dir'] . '/screenshot.png';

                // 修改启用状态
                $themeInfo['is_activated'] = $themeInfo['dir'] === $this->enabled['name'];

                // 主题是否可配置
                $themeInfo['is_config'] = $themeInfo['is_activated'] && [] !== $this->enabled['config'];

                $themeIndexPath = $themeDirs . '/functions.php';

                // 判断主题是否完整
                $themeInfo['is_complete'] = file_exists($themeIndexPath);

                return array_merge($themeInfo, Common::parseDoc($themeIndexPath));
            }, $themeDirs);
        }

        return $this->themeList;
    }

    /**
     * 插件配置
     *
     * @return void
     */
    public function config()
    {
        // 是否具备配置功能
        if ([] === $this->enabled['config']) {
            Notice::factory()->set('配置功能不存在', 'warning');
            $this->response->redirect('/admin/themes.php');
        }

        include NEBULA_ROOT_PATH . 'content/themes/' . $this->enabled['name'] . '/functions.php';

        $renderer = new Renderer();
        theme_config($renderer);
        $renderer->render($this->enabled['config']);
    }

    /**
     * 启用主题
     *
     * @return void
     */
    private function enable()
    {
        $themeName = $this->params('themeName');

        if ($themeName === $this->enabled['name']) {
            Notice::factory()->set('不能重复启用', 'warning');
            $this->response->redirect('/admin/themes.php');
        }

        // 获取主题配置
        $themeConfig = [];
        $themeFunctionsFile = NEBULA_ROOT_PATH . 'content/themes/' . $themeName . '/functions.php';
        if (file_exists($themeFunctionsFile)) {
            include $themeFunctionsFile;

            if (function_exists('theme_config')) {
                $renderer = new Renderer();
                theme_config($renderer);
                $themeConfig = $renderer->getValues();
            }
        }

        // 提交修改
        $this->db
            ->update('options', ['value' => serialize(['name' => $themeName, 'config' => $themeConfig])])
            ->where(['name' => 'theme'])
            ->execute();

        Notice::factory()->set('启用成功', 'success');
        $this->response->redirect('/admin/themes.php');
    }

    /**
     * 更新主题配置
     *
     * @return void
     */
    private function updateConfig()
    {
        $data = $this->request->post();

        $rules = [];
        foreach (array_keys($this->enabled['config']) as $value) {
            $rules[$value] = [['type' => 'required', 'message' => '「' . $value . '」不能为空']];
        }
        $validate = new Validate($data, $rules);
        if (!$validate->run()) {
            Notice::factory()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/theme-config.php');
        }

        foreach (array_keys($this->enabled['config']) as $value) {
            $this->enabled['config'][$value] = $data[$value];
        }

        // 提交修改
        $this->db
            ->update('options', ['value' => serialize($this->enabled)])
            ->where(['name' => 'theme'])
            ->execute();

        Notice::factory()->set('修改成功', 'success');
        $this->response->redirect('/admin/themes.php');
    }

    /**
     * 行动方法
     *
     * @return void
     */
    public function action()
    {
        $action = $this->params('action');

        // 启用主题
        $this->on('enable' === $action)->enable();

        // 更新主题配置
        $this->on('update-config' === $action)->updateConfig();
    }
}
