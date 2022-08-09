<?php

namespace Nebula\Widgets\Options;

use Nebula\Widget;

class Method extends Widget
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
    public function deleteOption($name)
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
    public function deleteOptions($names)
    {
        foreach ($names as $name) {
            $this->deleteOption($name);
        }
    }
}
