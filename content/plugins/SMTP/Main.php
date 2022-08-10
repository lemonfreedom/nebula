<?php

namespace Content\Plugins\SMTP;

use Nebula\Helpers\Validate;
use Nebula\Plugin;
use Nebula\Response;
use Nebula\Widgets\Cache;
use Nebula\Widgets\Notice;
use Nebula\Widgets\Option;

/**
 * name: SMTP 邮件
 * url: https://www.nebulaio.com/
 * description: 让系统支持邮件通知，具体功能有：注册邮件验证
 * version: 1.0
 * author: Noah Zhang
 * author_url: http://www.nebulaio.com/
 */
class Main
{
    /**
     * 启用插件
     *
     * @return void
     */
    public static function activate()
    {
        // 选项配置
        Plugin::factory('admin/options.php')->tab = __CLASS__ . '::tabRender';
        Plugin::factory('admin/options.php')->tabContent = __CLASS__ . '::tabContentRender';

        // 注册表单
        \Nebula\Plugin::factory('admin/register.php')->emailFormItem =  __CLASS__ . '::emailFormItemRender';

        // 更新配置
        Plugin::factory('includes/Widgets/Option.php')->update = __CLASS__ . '::update';

        // 数据初始化
        Option::factory()->set('smtp', serialize([
            'host' => '',
            'port' => '',
            'username' => '',
            'password' => '',
            'name' => '',
            'email' => '',
        ]));
    }

    /**
     * 停用插件
     *
     * @return void
     */
    public static function deactivate()
    {
        // 删除数据
        Option::factory()->delete('smtp');
    }

    /**
     * 插件配置
     *
     * @param $renderer 渲染器
     * @return void
     */
    public static function config($renderer)
    {
    }

    /**
     * tab 渲染
     * @param $data 数据
     * @return void
     */
    public static function tabRender($data)
    {
        $action = $data['action'];
        include __DIR__ . '/views/tab.php';
    }

    /**
     * tab 内容渲染
     */
    public static function tabContentRender($data)
    {
        $option = Option::factory();
        $action = $data['action'];
        include __DIR__ . '/views/tab-content.php';
    }

    /**
     * 注册邮箱表单渲染
     */
    public static function emailFormItemRender()
    {
        $cache = Cache::factory();
        include __DIR__ . '/views/register-email-form.php';
    }

    /**
     * 更新配置
     *
     * @param $data 数据
     * @return void
     */
    public static function update($data)
    {
        $optionName = $data['optionName'];

        if ('smtp' === $optionName) {
            $response = Response::getInstance();

            $data = $data['data'];

            $validate = new Validate($data, [
                'host' => [
                    ['type' => 'required', 'message' => '主机名不能为空'],
                ],
                'port' => [
                    ['type' => 'required', 'message' => '端口不能为空'],
                ],
                'username' => [
                    ['type' => 'required', 'message' => '用户名不能为空'],
                ],
                'password' => [
                    ['type' => 'required', 'message' => '密码不能为空'],
                ],
                'name' => [
                    ['type' => 'required', 'message' => '发件人名称不能为空'],
                ],
                'email' => [
                    ['type' => 'required', 'message' => '发件人邮箱不能为空'],
                ],
            ]);

            if (!$validate->run()) {
                Notice::factory()->set($validate->result[0]['message'], 'warning');
                $response->redirect('/admin/options.php?action=smtp');
            }

            // 更新
            Option::factory()->set('smtp', serialize([
                'host' => $data['host'],
                'username' => $data['username'],
                'password' => $data['password'],
                'port' => $data['port'],
                'name' => $data['name'],
                'email' => $data['email'],
            ]));

            Notice::factory()->set('保存成功', 'success');
            $response->redirect('/admin/options.php?action=smtp');
        }
    }
}
