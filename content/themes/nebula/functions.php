<?php

/**
 * name: Nebula 主题
 * url: https://www.nebulaio.com/
 * version: 1.0
 * author: Noah Zhang
 * author_url: http://www.nebulaio.com/
 */

if (!defined('NEBULA_ROOT_PATH')) exit;

function theme_config($renderer)
{
    $renderer->setValues([
        'val1' => '1',
        'val2' => '2',
        'val3' => '3',
        'val4' => '4',
        'val5' => '5',
    ]);

    $renderer->setTemplate(function ($data) {
        include __DIR__ . '/config.php';
    });
}
