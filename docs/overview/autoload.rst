########################################
自动加载
########################################

Nebula 提供一个非常灵活的自动加载器，不需要手动 ``requires()`` 即可引入类文件。自动加载在 ``includes/Common.php`` 中注册。

自动加载符合 `PSR4 <https://www.php-fig.org/psr/psr-4/>`_ 规范加载类。

.. note::

    以下目录结构使用别名命名空间

    * admin => **Admin**
    * content => **Content**
    * plugins => **Plugins**
    * themes => **Themes**
    * includes => **Nebula**
