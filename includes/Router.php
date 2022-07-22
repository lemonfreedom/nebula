<?php

namespace Nebula;

use Nebula\Widgets\Action;

class Router
{
    /**
     * 路由分发
     *
     * @return void
     */
    public static function dispatch()
    {
        $pathInfo = Request::getInstance()->pathinfo;
        $routeFragment = explode('/', $pathInfo);
        Action::alloc($routeFragment);
    }
}
