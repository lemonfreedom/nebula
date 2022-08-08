<?php

namespace Nebula\Widgets\Themes;

use Nebula\Common;
use Nebula\Helpers\Renderer;
use Nebula\Widgets\Database;
use Nebula\Widgets\Notice;
use Nebula\Widgets\Options\Method as OptionsMethod;

class Method extends Database
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
    private $themeList = null;

    public function init()
    {
        $this->enabledList = OptionsMethod::factory()->get('theme');
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
                $themeInfo['is_activated'] = $themeInfo['dir'] === $this->enabledList['name'];

                // 主题是否可配置
                $themeInfo['is_config'] = $themeInfo['is_activated'] && [] !== $this->enabledList['config'];

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
     */
    public function config()
    {
        // 是否具备配置功能
        if ([] === $this->enabledList['config']) {
            Notice::factory()->set('配置功能不存在', 'warning');
            $this->response->redirect('/admin/themes.php');
        }

        include NEBULA_ROOT_PATH . 'content/themes/' . $this->enabledList['name'] . '/functions.php';

        $renderer = new Renderer();
        theme_config($renderer);
        $renderer->render($theme['config']);
    }
}
