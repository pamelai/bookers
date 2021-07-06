<?php

namespace pifp\Core;

class App
{
    private static $rootPath;
    private static $appPath;
    private static $publicPath;
    private static $viewsPath;
    private static $urlPath;

    protected $request;

    public function __construct($rootPath)
    {
        self::$rootPath = $rootPath;
        self::$appPath = $rootPath . '/app';
        self::$publicPath = $rootPath . '/public';

        self::$urlPath = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] .  $_SERVER['SCRIPT_NAME'];

        self::$urlPath = substr(self::$urlPath, 0, -9);
    }

    public function run()
    {
        $this->request = new Request();

        if(Route::exists($this->request->getMethod(), $this->request->getRequestedUrl())) {
            $controller = Route::getController($this->request->getMethod(), $this->request->getRequestedUrl());
            $this->executeController($controller);
        } else {
            throw new \Exception("No existe la ruta especificada.");
        }
    }

    public function executeController($controller)
    {
        $controllerData = explode('@', $controller);
        $controllerName = $controllerData[0];
        $controllerMethod = $controllerData[1];

        $controllerName = "\\pifp\\Controllers\\" . $controllerName;
        $controllerObject = new $controllerName;
       $controllerObject->{$controllerMethod}();
    }

    public static function redirect($path = '')
    {
        header('Location: ' . self::getUrlPath() . $path);
        exit;
    }

    public static function urlTo($path = '')
    {
        if(strpos($path, '/') === 0) {
            $path = substr($path, 1);
        }

        return self::$urlPath . $path;
    }

    public static function getRootPath()
    {
        return self::$rootPath;
    }

    public static function getAppPath()
    {
        return self::$appPath;
    }

    public static function getPublicPath()
    {
        return self::$publicPath;
    }

    public static function getViewsPath()
    {
        return self::$viewsPath;
    }

    public static function getUrlPath()
    {
        return self::$urlPath;
    }
}