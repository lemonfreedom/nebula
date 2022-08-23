####################
程序获取
####################

#. :download:`直接下载「v0.1.0-alpha」 <https://github.com/nbacms/nebula/releases/download/v0.1.0-alpha/v0.1.0-alpha.zip>`
#. `Github <https://github.com/nbacms/nebula/>`_

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
