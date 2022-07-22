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

                    Common::errorRender($e);
                    exit;
                });
            }
        }

        /**
         * 错误输出
         *
         * @param object $exception 错误输出
         * @return void
         */
        private static function errorRender($exception)
        {
            $code = $exception->getCode() ?? '500';
            $message = $exception->getMessage();

            if ($exception instanceof \Nebula\Database\Exception) {
                $message = 'Database server error.';
            }

            echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$code}</title>
    <style>
        html {
            padding: 8%;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
            color: #666;
            background: #F6F6F3;
            word-break: break-all;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            max-width: 560px;
            padding: 1.6rem 2rem;
            margin: 0 auto;
            background: #FFF;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="container">{$message}</div>
</body>
</html>\n
HTML;
            exit;
        }
    }
}
