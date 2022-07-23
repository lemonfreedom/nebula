<?php

namespace Nebula\Widgets;

class Options extends Base
{
    public function execute()
    {
        $options = $this->db->select('options', ['name', 'value']);
        foreach ($options as $option) {
            $this->{$option['name']} = $option['value'];
        }
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
        $this->$name = $value;
        if ($this->db->has('options', ['name' => $name])) {
            $this->db->update('options', ['value' => $value], ['name' => $name]);
        } else {
            $this->db->insert('options', ['name' => $name, 'value' => $value]);
        }
    }

    /**
     * 设置多项配置
     *
     * @param array $options 配置列表
     * @return void
     */
    public function setOptions($options)
    {
        foreach ($options as $name => $value) {
            $this->setOption($name, $value);
        }
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
}
