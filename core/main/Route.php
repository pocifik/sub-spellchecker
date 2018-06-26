<?php

namespace core\main;

class Route
{
    public function init()
    {
        $path = explode('/', $_SERVER['REQUEST_URI']);
        if (count($path) == 2) {
            $controller_name = 'main';
            $action_name = $path[1];
            if (empty($action_name)) {
                $action_name = 'index';
            }

            $controller_name = $this->controllerName($controller_name);
            $action_name     = $this->actionName($action_name);

            $controller = new $controller_name;
            $controller->$action_name();
        }
    }

    protected function controllerName($name)
    {
        return '\controllers\\' . ucfirst($name) . 'Controller';
    }

    protected function actionName($name)
    {
        return 'action' . ucfirst($name);
    }
}