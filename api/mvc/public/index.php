<?php
session_start();
date_default_timezone_set("America/Argentina/Buenos_Aires");

// Antes que nada, requerimos el autoload.
require __DIR__ . '/../autoload.php';
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../image.intervention/vendor/autoload.php';

// Guardamos la ruta absoluta de base del proyecto.
$rootPath = realpath(__DIR__ . '/../');

// Normalizamos las \ a /
$rootPath = str_replace('\\', '/', $rootPath);

// Requerimos las rutas de la aplicación.
require $rootPath . '/app/routes.php';

// Instanciamos nuestra App.
$app = new \pifp\Core\App($rootPath);

// Arrancamos la App.
$app->run();