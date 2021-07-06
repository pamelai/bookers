<?php

spl_autoload_register(function ($clase){
   $rutaArchivo = __DIR__ . '/../classes/' . $clase . '.php';

   if(file_exists($rutaArchivo)){
       require_once $rutaArchivo;
   }
});