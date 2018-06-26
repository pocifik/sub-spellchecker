<?php

namespace core;

use core\main\Route;
use core\main\Session;

/**
 * Class Core
 * @package core
 */
class Core
{
    /**
     * @var Route $route
     */
    public static $route;
    /**
     * @var Session $session
     */
    public static $session;

    public function init()
    {
        $route = new Route();
        $session = new Session();

        Core::$route = $route;
        Core::$session = $session;

        $session->init();
        $route->init();
    }
}