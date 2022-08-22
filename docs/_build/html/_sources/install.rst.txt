********************
安装
********************

程序获取
====================

#. :download:`直接下载 <_static/logo.png>`
#. https://github.com/nbacms/nebula/

开始安装
====================

环境准备
--------------------

PHP>=7.4、MySQL、Nginx「推荐」或 Apache。

安装流程
--------------------

* 自动安装，访问程序地址，如程序未初始化则自动跳转至安装程序，如需重复安装，则需删除 ``config.php`` 文件。

* 手动安装，新建 ``config.php`` 文件，写入如下配置。

.. code:: php

    <?php
    // 调试模式
    define('NEBULA_DEBUG', true);

    // 数据库初始化
    \Nebula\Helpers\MySQL::getInstance()->init([
    'dbname' => 'nebula',
    'host' => 'localhost',
    'port' => '3306',
    'username' => 'root',
    'password' => 'root',
    'prefix' => 'nebula_',
    ]);

配置伪静态
--------------------

程序安装完成后，还需配置伪静态才能正常访问。

如果您使用 `Nginx`，则需在你的 `Nginx` 配置里添加如下配置：

.. code::

    location / {
        try_files  $uri $uri/ /index.php?$query_string;
    }

如果您使用 `Apache`，则需新建 ``.htaccess`` 文件，内容如下：

.. code::

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?/$1 [L]
