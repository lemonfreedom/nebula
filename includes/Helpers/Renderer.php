<?php

/**
 * This file is part of Nebula.
 *
 * (c) 2022 NoahZhang <nbacms@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Nebula\Helpers;

/**
 * 渲染器
 */
class Renderer
{
    /**
     * 数据集
     *
     * @var array
     */
    private $data = [];

    /**
     * 模版回调集
     *
     * @var array
     */
    private $templateCallback = [];

    /**
     * 设置数据
     *
     * @param array $values 数据
     * @return $this
     */
    public function setValue($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * 设置多项数据
     *
     * @param array $values 数据
     * @return $this
     */
    public function setValues($values)
    {
        $this->data = array_merge($this->data, $values);

        return $this;
    }

    /**
     * 获取数据
     *
     * @return array
     */
    public function getValues()
    {
        return $this->data;
    }

    /**
     * 设置模版
     *
     * @param $callback 模板回调函数
     * @return void
     */
    public function setTemplate($callback)
    {
        array_push($this->templateCallback, $callback);
    }

    /**
     * 渲染模板
     *
     * @param null|array  $data 渲染数据
     * @return void
     */
    public function render($data = null)
    {
        foreach ($this->templateCallback as $callback) {
            call_user_func($callback, null === $data ? $this->data : $data);
        }
    }
}
