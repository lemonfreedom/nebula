<?php

namespace Nebula\Helpers;

class Template
{
    /**
     * 创建一个 HTML 元素
     *
     * @param string $name 标签名
     * @param array $attributes 标签属性
     * @param bool|string|array $content 插槽内容
     * @return string
     */
    public static function createElement($name, $attributes = [], $content = false)
    {
        $content = is_array($content) ? implode('', $content) : $content;

        $attribute = "";
        foreach ($attributes as $key => $value) {
            $value = is_array($value) ? implode(' ', $value) : $value;
            $attribute .= is_bool($value) ? ($value ? $key : '') : " {$key}=\"{$value}\"";
        }

        return false !== $content ? "<{$name}{$attribute}>{$content}</{$name}>" : "<{$name}{$attribute} />";
    }

    /**
     * 标签页头渲染
     *
     * @param array $tabs tabs 数据
     * @param array $activeName 当前激活名称
     * @param string $afterContent 追加内容
     * @return string
     */
    public static function tabs($tabs, $activeName = '', $afterContent = '')
    {
        $tabContent = '';

        foreach ($tabs as $tab) {
            if ($tab['has']) {
                $tabContent .= self::createElement(
                    'div',
                    ['class' => $activeName === $tab['active'] ? ['tab', 'active'] : ['tab']],
                    self::createElement('a', ['href' => $tab['path']], $tab['name'])
                );
            }
        }

        return self::createElement(
            'div',
            ['class' => 'tabs'],
            self::createElement(
                'div',
                ['class' => 'scroll'],
                $tabContent
            )
        ) . $afterContent . "\n";
    }

    /**
     * 表单渲染
     *
     * @param string $action 表单提交地址
     * @param string|array $content 表单内容
     * @param string $method 提交方式
     * @return string
     */
    public static function form($action, $content, $method = 'POST')
    {
        return self::createElement('form', [
            'class' => 'form',
            'action' => $action,
            'method' => $method,
        ], $content);
    }

    /**
     * 表单项渲染
     *
     * @param $string|array $content 内容
     * @param bool|string $for for 属性
     * @param bool|string $label label
     * @param bool|string $sublabel sublabel
     * @return string
     */
    public static function formItem($content, $for = false, $label = false, $sublabel = false)
    {
        return self::createElement('div', ['class' => 'form-item'], [
            false !== $label ? self::createElement('label', [
                'class' => 'form-label',
                'for' => $for,
            ], $label) : '',
            $content,
            false !== $sublabel ? self::createElement('div', [
                'class' => 'form-sublabel',
            ], $sublabel) : '',
        ]);
    }

    /**
     * 渲染一个 input
     *
     * @param string $name name 属性
     * @param string $value value 属性
     * @param string $type type 属性
     * @return string
     */
    public static function input($name, $value = '', $type = 'text')
    {
        return self::createElement('input', [
            'class' => 'input',
            'id' => $name,
            'name' => $name,
            'value' => $value,
            'type' => $type,
        ]);
    }

    /**
     * 渲染一个 select
     *
     * @param string $name name 属性
     * @param array $options 选项列表
     * @param string $value value 属性
     * @return string
     */
    public static function select($name, $options = [], $value = '')
    {
        $optionContent = [];
        foreach ($options as $option) {
            array_push($optionContent, self::createElement('option', ['value' => $option['value'], 'selected' => $value === $option['value']], $option['name']));
        }

        return self::createElement('select', [
            'class' => 'select',
            'id' => $name,
            'name' => $name,
        ], $optionContent);
    }

    /**
     * 渲染一个 radio
     *
     * @param string $name name 属性
     * @param array $radios 选项列表
     * @param string $value value 属性
     * @return string
     */
    public static function radio($name, $radios = [], $value = '')
    {
        $radioContent = [];
        foreach ($radios as $radio) {
            array_push($radioContent, self::createElement('label', ['class' => 'radio'], [
                self::createElement('input', ['type' => 'radio', 'name' => $name, 'value' => $radio['value'], 'checked' => $value === $radio['value']]),
                self::createElement('div', ['class' => 'checkmark'], ''),
                self::createElement('span', [], $radio['name']),
            ]));
        }

        return self::createElement('div', [
            'class' => 'radio-group',
        ], $radioContent);
    }

    /**
     * 渲染一个 button
     *
     * @param string $content 按钮内容
     * @param string $type 按钮类型
     * @return string
     */
    public static function button($content, $type = "button")
    {
        return self::createElement('button', ['class' => 'button', 'type' => $type], $content);
    }
}
