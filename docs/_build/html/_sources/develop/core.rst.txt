########################################
核心类
########################################

****************************************
includes/Common.php
****************************************

公共类

.. php:class:: Nebula\Common

    .. php:staticmethod:: init()

        初始化方法

    .. php:staticmethod:: randString($length[, $number = true, $lowerCase = true, $mixedCase = false, $specialChars = false])

        :param int $length: 字符串长度
        :param bool $number: 是否包含数字
        :param bool $lowerCase: 是否包含小写字母
        :param bool $mixedCase: 是否包含大写字母
        :param bool $specialChars: 是否包含特殊字符
        :returns: 生成的随机字符串
        :rtype: string

        生成随机字符串

    .. php:staticmethod:: hash($string[, $salt = null])

        :param string $string: 目标字符串
        :param null|string $salt: 32 位扰码
        :returns: 哈希值
        :rtype: string

        对字符串进行 hash 加密

    .. php:staticmethod:: hashValidate($from, $to)

        :param string $from: 源字符串
        :param string $to: 目标字符串
        :returns: 是否验证成功
        :rtype: bool

        验证 hash

    .. php:staticmethod:: parseDoc($path)

        :param string $path: 文件路径
        :returns: 注释信息
        :rtype: array

        文档头注释解析

****************************************
includes/Router.php
****************************************

路由类

.. php:class:: Nebula\Router

    .. php:staticmethod:: dispatch()

        路由分发，访问入口

****************************************
includes/Request.php
****************************************

****************************************
includes/Response.php
****************************************

****************************************
includes/Widget.php
****************************************

****************************************
includes/Plugin.php
****************************************
