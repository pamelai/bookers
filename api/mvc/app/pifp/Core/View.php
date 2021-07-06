<?php

namespace pifp\Core;

class View
{
    public static function render($__vista, $__data = [])
    {
        foreach ($__data as $key => $value) {
            ${$key} = $value;
        }
        require App::getViewsPath() . '/templates/header.php';

        require App::getViewsPath() . '/' . $__vista . ".php";

        require App::getViewsPath() . '/templates/footer.php';
    }

    public static function renderJson($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}