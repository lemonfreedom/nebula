<?php

namespace Nebula\Widgets;

use Nebula\Helpers\Cookie;
use Nebula\Widget;

class Notice extends Widget
{
    /**
     * 设置通知
     *
     * @param string $message 通知信息
     * @param string $type 通知类型
     * @return $this
     */
    public function set($message, $type = 'info')
    {
        $notice = [
            'type' => $type,
            'message' => $message,
        ];
        Cookie::set('nebula_notice', json_encode($notice));
        return $this;
    }
}
