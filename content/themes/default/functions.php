<?php

/**
 * name: Default
 * url: https://www.nebulaio.com/
 * description: 这是默认主题
 * version: 1.0
 * author: Noah Zhang
 * author_url: http://www.nebulaio.com/
 */

if (!defined('NEBULA_ROOT_PATH')) exit;

function theme_config($renderer)
{
    $renderer->setValues([
        'val1' => '默认值1',
        'val2' => '默认值2',
    ]);

    $renderer->setTemplate(function ($data) {
        include __DIR__ . '/config.php';
    });
}
