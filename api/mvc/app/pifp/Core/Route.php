<?php

namespace pifp\Core;

class Route
{
    protected static $routes = [
        'GET'       => [],
        'POST'      => [],
        'PUT'       => [],
        'DELETE'    => [],
    ];

    protected static $controllerAction;

    protected static $urlParameters = [];

    public static function add($method, $url, $controller)
    {
        $method = strtoupper($method);
        self::$routes[$method][$url] = $controller;
    }

    public static function exists($method, $url)
    {
        if(isset(self::$routes[$method][$url])) {
            return true;
        }
        else if(self::parameterizedRouteExists($method, $url)) {
            return true;
        } else {
            return false;
        }
    }

    public static function parameterizedRouteExists($method, $url)
    {
        $urlParts = explode('/', $url);

        foreach (self::$routes[$method] as $route => $controllerAction) {
            $routeParts = explode('/', $route);
            $routeMatches = true;
            $urlData = [];

            if(count($routeParts) != count($urlParts)) {
                $routeMatches = false;
            } else {
                foreach ($routeParts as $key => $part) {
                    if($routeParts[$key] != $urlParts[$key]) {
                        if(strpos($routeParts[$key], '{') === 0) {
                            $parameterName = substr($routeParts[$key], 1, -1);
                        $urlData[$parameterName] = $urlParts[$key];
                        } else {
                            $routeMatches = false;
                        }
                    }
                }
            }

            if($routeMatches) {
                self::$controllerAction = $controllerAction;
                self::$urlParameters = $urlData;

                return true;
            }
        }
        return false;
    }

    public static function getController($method, $url)
    {
        if(!is_null(self::$controllerAction)) {
            return self::$controllerAction;
        }

        return self::$routes[$method][$url];
    }

    public static function getUrlParameters()
    {
        return self::$urlParameters;
    }
}