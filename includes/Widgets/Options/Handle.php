<?php

namespace Nebula\Widgets\Options;

use Nebula\Widget;
use Nebula\Helpers\Validate;
use Nebula\Plugin;
use Nebula\Widgets\Notice;
use Nebula\Widgets\Users\Method as UsersMethod;

class Handle extends Widget
{
    /**
     * 更新基本选项
     *
     * @return void
     */
    public function update()
    {
        // 是否是管理员
        if (!UsersMethod::factory()->inRole(['0'])) {
            Notice::factory()->set('非法请求', 'error');
            $this->response->redirect('/admin');
        }

        $data = $this->request->post();

        $optionName = $this->params('optionName');

        // 注册表单验证插件
        Plugin::factory('includes/Widgets/Options/Handle.php')->update([
            'optionName' => $optionName,
            'data' => $data
        ]);

        if ('basic' === $optionName) {
            $validate = new Validate($data, [
                'title' => [
                    ['type' => 'required', 'message' => '站点名称不能为空'],
                ],
                'description' => [
                    ['type' => 'required', 'message' => '站点描述不能为空'],
                ],
                'allowRegister' => [
                    ['type' => 'required', 'message' => '是否允许注册不能为空'],
                ],
            ]);

            if (!$validate->run()) {
                Notice::factory()->set($validate->result[0]['message'], 'warning');
                $this->response->redirect('/admin/options.php');
            }

            // 站点名称
            Method::factory()->set('title', $data['title'] ?? $this->title);
            // 站点描述
            Method::factory()->set('description', $data['description'] ?? $this->description);
            // 是否允许注册
            Method::factory()->set('allowRegister', $data['allowRegister']);

            Notice::factory()->set('保存成功', 'success');
            $this->response->redirect('/admin/options.php');
        }
    }

    /**
     * 行动方法
     *
     * @return void
     */
    public function action()
    {
        $action = $this->params('action');

        // 更新选项
        $this->on($action === 'update')->update();
    }
}
