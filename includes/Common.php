<?php

namespace {
    // 定义类片段别名
    define('NEBULA_CLASS_FRAGMENT_ALIASES', [
        'Nebula' => 'includes',
        'Content' => 'content',
        'Themes' => 'themes',
        'Plugins' => 'plugins',
    ]);

    // 自动加载
    spl_autoload_register(function ($className) {
        $classFragments = explode('\\', $className);

        array_walk($classFragments, function (&$fragment) {
            if (isset(NEBULA_CLASS_FRAGMENT_ALIASES[$fragment])) {
                $fragment = NEBULA_CLASS_FRAGMENT_ALIASES[$fragment];
            }
        });

        $filename = NEBULA_ROOT_PATH . implode('/', $classFragments) . '.php';
        if (file_exists($filename)) {
            include $filename;
        }
    });
}

namespace Nebula {
    class Common
    {
        /**
         * 初始化
         *
         * @return void
         */
        public static function init()
        {
            // 初始化异常处理
            if (defined(DEBUG) || false === DEBUG) {
                set_exception_handler(function ($e) {
                    ob_end_clean();
                    ob_start(function ($buffer) {
                        return $buffer;
                    });
                    Response::getInstance()->render500();
                    exit;
                });
            }
        }
    }
}
