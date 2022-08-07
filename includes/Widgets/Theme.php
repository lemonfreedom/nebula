<?php

namespace Nebula\Widgets;

use Nebula\Common;
use Nebula\Helpers\Renderer;
use Nebula\Helpers\Validate;

class Theme extends Database
{
    /**
     * 插件列表
     *
     * @var null|array
     */
    private $themeList = null;

    /**
     * 获取主题列表
     *
     * @return array 主题列表
     */
    public function getThemeList()
    {
        if (null === $this->themeList) {
            // 已启用主题
            $theme = Option::alloc()->get('theme');

            // 主题目录列表
            $themeDirs = glob(NEBULA_ROOT_PATH . 'content/themes/*/');

            // 主题信息
            $this->themeList = array_map(function ($themeDirs) use ($theme) {
                $themeInfo = [];
                // 主题目录
                $themeInfo['dir'] = basename($themeDirs);

                $themeInfo['screenshot_url'] = '/content/themes/' . $themeInfo['dir'] . '/screenshot.png';

                // 修改启用状态
                $themeInfo['is_activated'] = $themeInfo['dir'] === $theme['name'];

                // 主题是否可配置
                $themeInfo['is_config'] = $themeInfo['is_activated'] && [] !== $theme['config'];

                $themeIndexPath = $themeDirs . '/functions.php';

                // 判断主题是否完整
                $themeInfo['is_complete'] = file_exists($themeIndexPath);

                return array_merge($themeInfo, Common::parseDoc($themeIndexPath));
            }, $themeDirs);
        }

        return $this->themeList;
    }

    /**
     * 启用主题
     *
     * @return void
     */
    private function enable()
    {
        // 已启用主题
        $theme = Option::alloc()->get('theme');

        $themeName = $this->params['themeName'];

        if ($themeName === $theme['name']) {
            Notice::alloc()->set('不能重复启用', 'warning');
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
        $this->db->update('options', ['value' => serialize(['name' => $themeName, 'config' => $themeConfig])], ['name' => 'theme']);

        Notice::alloc()->set('启用成功', 'success');
        $this->response->redirect('/admin/themes.php');
    }

    //
    private function updateConfig()
    {
        // 已启用主题
        $theme = Option::alloc()->get('theme');

        $data = $this->request->post();

        $rules = [];
        foreach (array_keys($theme['config']) as $value) {
            $rules[$value] = [['type' => 'required', 'message' => '「' . $value . '」不能为空']];
        }
        $validate = new Validate($data, $rules);
        if (!$validate->run()) {
            Notice::alloc()->set($validate->result[0]['message'], 'warning');
            $this->response->redirect('/admin/theme-config.php');
        }

        foreach (array_keys($theme['config']) as $value) {
            $theme['config'][$value] = $data[$value];
        }

        // 提交修改
        $this->db->update('options', ['value' => serialize($theme)], ['name' => 'theme']);

        Notice::alloc()->set('修改成功', 'success');
        $this->response->redirect('/admin/themes.php');
    }

    /**
     * 插件配置
     */
    public function config()
    {
        // 启用主题信息
        $theme = Option::alloc()->get('theme');

        // 是否具备配置功能
        if ([] === $theme['config']) {
            Notice::alloc()->set('配置功能不存在', 'warning');
            $this->response->redirect('/admin/themes.php');
        }

        include NEBULA_ROOT_PATH . 'content/themes/' . $theme['name'] . '/functions.php';

        $renderer = new Renderer();
        theme_config($renderer);
        $renderer->render($theme['config']);
    }

    /**
     * 行动方法
     *
     * @return $this
     */
    public function action()
    {
        $action = $this->params['action'];

        // 启用主题
        $this->on($action === 'enable')->enable();

        // 更新主题配置
        $this->on($action === 'update-config')->updateConfig();

        return $this;
    }
}
