<?php

namespace pifp\Core;

class Request
{
    protected $requestedUrl;

    protected $method;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];

        $rutaAbsoluta = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'];

        $this->requestedUrl = str_replace(App::getPublicPath(), '', $rutaAbsoluta);
    }

    public function getRequestedUrl()
    {
        return $this->requestedUrl;
    }

    public function getMethod()
    {
        return $this->method;
    }
}