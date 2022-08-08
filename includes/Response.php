<?php

namespace Nebula;

use Exception;
use Nebula\Widgets\Options\Method as OptionsMethod;

class Response
{
    /**
     * 单例实例
     *
     * @var Response
     */
    private static $instance;

    private function __construct()
    {
    }

    /**
     * 设置 cookie
     *
     * @param string $name
     * @param string $value
     * @param int $expiresOrOptions
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return $this
     */
    public function setCookie($name, $value, $expiresOrOptions, $path, $domain, $secure, $httponly)
    {
        setrawcookie($name, rawurlencode($value), $expiresOrOptions, $path, $domain, $secure, $httponly);
        return $this;
    }

    /**
     * 加载视图
     *
     * @param string $fileName 文件名
     * @param array $data 视图数据
     * @return $this
     */
    public function render($fileName, $data = [])
    {
        // 当前主题
        $theme = OptionsMethod::factory()->get('theme');
        $data['theme_config'] = $theme['config'];

        header('Content-Type: text/html; charset=utf-8');

        ob_start();
        $filePath = NEBULA_ROOT_PATH . 'content/themes/' . $theme['name'] . '/' . $fileName . '.php';
        if (file_exists($filePath)) {
            extract($data);
            include $filePath;
        } else {
            throw new Exception("主题缺少 {$fileName} 文件");
        }
        $html = ob_get_contents();
        ob_end_clean();
        echo $html;
        return $this;
    }

    /**
     * 响应 JSON 数据
     *
     * @param array $data 数据
     * @return void
     */
    public function sendJSON($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    /**
     * 重定向
     *
     * @param string $url 重定向地址
     * @param callable $callback 回调函数
     * @return void
     */
    public function redirect($url, $callback = null)
    {
        header('Location:' . $url);

        if (null !== $callback) {
            call_user_func($callback, $url);
        }

        exit;
    }

    /**
     * 获取单例实例
     *
     * @return Response
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
