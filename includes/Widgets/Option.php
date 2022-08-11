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

use Nebula\Widget;
use Nebula\Plugin;
use Nebula\Helpers\Validate;
use Nebula\Widgets\Notice;
use Nebula\Widgets\User;

class Option extends Widget
{
    /**
     * @var array
     */
    private $options = [];

    public function init()
    {
        $this->options = $this->db
            ->select('options', ['name', 'value'])
            ->execute();

        foreach ($this->options as $index => $option) {
            // 布尔选项处理
            if ('allowRegister' === $option['name']) {
                $this->options[$index]['value'] = '1' === $this->options[$index]['value'];
            }

            // 选项去序列化处理
            if (in_array($option['name'], ['smtp', 'plugins', 'theme'])) {
                $this->options[$index]['value'] = unserialize($this->options[$index]['value']);
            }
        }
    }

    /**
     * 获取选项
     *
     * @param null|string $name 选项名
     * @param null|string $defaultValue 默认值
     * @return null|string|array
     */
    public function get($name = null, $defaultValue = null)
    {
        if (null === $name) {
            return $this->options;
        } else {
            return array_values(array_filter($this->options, function ($option) use ($name) {
                return $option['name'] === $name;
            }))[0]['value'] ?? $defaultValue;
        }
    }

    /**
     * 设置配置项
     *
     * @param string $name 选项名
     * @param string $value 选项值
     * @return void
     */
    public function set($name, $value)
    {
        $index = array_search($name, array_map(function ($option) {
            return $option['name'];
        }, $this->options));

        if (false === $index) {
            $this->db->insert('options', [
                'name' => $name,
                'value' => $value,
            ]);
            array_push($this->options, ['name' => $name, 'value' => $value]);
        } else {
            if ($this->options[$index]['value'] !== $value) {
                $this->db
                    ->update('options', [
                        'value' => $value,
                    ])
                    ->where(['name' => $name])
                    ->execute();
                $this->options[$index]['value'] = $value;
            }
        }
    }


    /**
     * 设置多个选项项
     *
     * @param array $options 选项列表
     * @return void
     */
    public function sets($options)
    {
        foreach ($options as $name => $value) {
            $this->set($name, $value);
        }
    }

    /**
     * 删除选项
     *
     * @param string $name 选项名称
     * @return void
     */
    public function delete($name)
    {
        $this->db
            ->delete('options')
            ->where(['name' => $name])
            ->execute();
    }

    /**
     * 删除多项选项
     *
     * @param array $names 选项名称列表
     * @return void
     */
    public function deletes($names)
    {
        foreach ($names as $name) {
            $this->delete($name);
        }
    }

    /**
     * 更新基本选项
     *
     * @return void
     */
    private function update()
    {
        // 是否是管理员
        if (!User::factory()->inRole(['0'])) {
            Notice::factory()->set('非法请求', 'error');
            $this->response->redirect('/admin');
        }

        $data = $this->request->post();

        $optionName = $this->params('optionName');

        // 注册表单验证插件
        Plugin::factory('includes/Widgets/Option.php')->update([
            'optionName' => $optionName,
            'data' => $data,
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
            $this->set('title', $data['title'] ?? $this->title);
            // 站点描述
            $this->set('description', $data['description'] ?? $this->description);
            // 是否允许注册
            $this->set('allowRegister', $data['allowRegister']);

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
        $this->on('update' === $action)->update();
    }
}
