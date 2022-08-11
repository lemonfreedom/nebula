<?php

/**
 * This file is part of Nebula.
 *
 * (c) 2022 nbacms <nbacms@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

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

    use Nebula\Widgets\Cache;
    use Nebula\Widgets\Option;

    class Common
    {
        public static function init()
        {
            // 初始化异常处理
            if (defined(NEBULA_DEBUG) || false === NEBULA_DEBUG) {
                set_exception_handler(function ($e) {
                    ob_end_clean();
                    ob_start(function ($buffer) {
                        return $buffer;
                    });
                    Response::getInstance()->render('500');
                    exit;
                });
            }

            // 开启缓存
            Cache::factory();

            // 插件初始化
            Plugin::init(Option::factory()->get('plugins'));
        }

        /**
         * 生成随机字符串
         *
         * @param int $length 字符串长度
         * @param bool $number 是否有数字
         * @param bool $lowerCase 是否有小写字母
         * @param bool $mixedCase 是否有大写字母
         * @param bool $specialChars 是否有特殊字符
         * @return string
         */
        public static function randString($length,  $number = true, $lowerCase = true, $mixedCase = false, $specialChars = false)
        {
            $chars = '';

            if ($number) {
                $chars .= '0123456789';
            }

            if ($lowerCase) {
                $chars .= 'abcdefghijklmnopqrstuvwxyz';
            }

            if ($mixedCase) {
                $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            }

            if ($specialChars) {
                $chars .= '.!@#$%^&*()';
            }

            $result = '';
            $max = strlen($chars) - 1;
            for ($i = 0; $i < $length; $i++) {
                $result .= $chars[rand(0, $max)];
            }

            return $result;
        }

        /**
         * 对字符串进行 hash 加密
         *
         * @param string $string 目标字符串
         * @param null|string $salt 32 位扰码
         * @return string 哈希值
         */
        public static function hash($string, $salt = null)
        {
            $salt = null === $salt ? self::randString(32) : $salt;
            $hash = '';
            $pos = 0;
            $saltPos = 0;

            while ($pos < strlen($string)) {
                if ($saltPos === strlen($salt)) {
                    $saltPos = 0;
                }

                $hash .= chr(ord($string[$pos]) + ord($salt[$saltPos]));

                $pos++;
                $saltPos++;
            }

            return $salt . md5($hash);
        }

        /**
         * 验证 hash
         *
         * @param string $from 源字符串
         * @param string $to 目标字符串
         * @return bool 是否验证成功
         */
        public static function hashValidate($from, $to)
        {
            return self::hash($from, substr($to, 0, 32)) === $to;
        }

        /**
         * 格式化 DOC
         *
         * @param $path 文件路径
         * @return array
         */
        public static function parseDoc($path)
        {
            $info = [
                'name' => '未知',
                'url' => '',
                'description' => '',
                'version' => '未知',
                'author' => '未知',
                'author_url' => '',
            ];

            if (!file_exists($path)) {
                return $info;
            }

            $tokens = token_get_all(file_get_contents($path));
            $isDoc = false;
            foreach ($tokens as $token) {
                if (!$isDoc && $token[0] === T_DOC_COMMENT) {
                    if (strpos($token[1], 'name')) {
                        $isDoc = true;

                        // 名称
                        preg_match('/name:(.*)[\\r\\n]/', $token[1], $matches);
                        $info['name'] = trim($matches[1] ?? '未知');

                        // 地址
                        preg_match('/url:(.*)[\\r\\n]/', $token[1], $matches);
                        $info['url'] = trim($matches[1] ?? '');

                        // 描述
                        preg_match('/description:(.*)[\\r\\n]/', $token[1], $matches);
                        $info['description'] = trim($matches[1] ?? '未知');

                        // 版本
                        preg_match('/version:(.*)[\\r\\n]/', $token[1], $matches);
                        $info['version'] = trim($matches[1] ?? '未知');

                        // 作者
                        preg_match('/author:(.*)[\\r\\n]/', $token[1], $matches);
                        $info['author'] = trim($matches[1] ?? '未知');

                        // 作者地址
                        preg_match('/author_url:(.*)[\\r\\n]/', $token[1], $matches);
                        $info['author_url'] = trim($matches[1] ?? '');
                    }
                }
            }

            return $info;
        }
    }
}
