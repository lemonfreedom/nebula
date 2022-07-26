<?php

namespace Nebula\Widgets;

use Nebula\Widget;

class Options extends Widget
{
    public function execute()
    {
        $options = $this->db->select('options', ['name', 'value']);

        foreach ($options as $option) {
            $this->{$option['name']} = $option['value'];
        }

        $this->smtp = unserialize($this->smtp);
    }

    /**
     * 设置配置项
     *
     * @param string $name 配置键
     * @param string $value 配置值
     * @return void
     */
    public function setOption($name, $value)
    {
        if (isset($this->$name)) {
            if ($this->$name !== $value) {
                $this->db->update('options', ['value' => $value], ['name' => $name]);
            }
        } else {
            $this->db->insert('options', ['name' => $name, 'value' => $value]);
        }

        $this->$name = $value;
    }

    /**
     * 删除配置项
     *
     * @param string $name 配置键
     * @return void
     */
    public function deleteOption($name)
    {
        $this->db->delete('options', ['name' => $name]);
    }

    /**
     * 删除多项配置
     *
     * @param array $names 配置键列表
     * @return void
     */
    public function deleteOptions($names)
    {
        foreach ($names as $name) {
            $this->deleteOption($name);
        }
    }

    /**
     * 布尔配置解析
     *
     * @param string $name 配置键
     * @return bool
     */
    public function boolParse($name)
    {
        if (isset($this->$name)) {
            return $this->$name === '1';
        } else {
            return false;
        }
    }

    /**
     * 更新配置
     *
     * @return void
     */
    public function update()
    {
        $params = $this->request->post();

        // 站点名称
        $this->setOption('title', $params['title'] ?? $this->title);
        // 站点描述
        $this->setOption('description', $params['description'] ?? $this->description);
        // 是否允许注册
        $this->setOption('allowRegister', $params['allowRegister'] ?? '0');

        Notice::alloc()->set('保存成功', 'success');
        $this->response->redirect('/admin/options.php');
    }

    /**
     * 行动方法
     *
     * @return $this
     */
    public function action()
    {
        $action = $this->params['action'];

        $this->on($action === 'update')->update();

        return $this;
    }
}
