<?php

namespace core\base_classes;

class BaseController
{
    public function render($view, $data = [], $layout = 'main')
    {
        ob_start();
        include __DIR__ . '/../../views/' . $view . '.php';
        $content = ob_get_contents();
        ob_end_clean();
        ob_start();
        include __DIR__ . '/../../views/layout/' . $layout . '.php';
        $var = ob_get_contents();
        ob_end_clean();
        echo $var;
        return true;
    }

    public function redirect($url)
    {
        header('Location: '.$url);
    }
}