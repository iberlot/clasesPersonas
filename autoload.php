<?php

// Definimos un autoload.
spl_autoload_register(function($className) {
    // Cambiamos las \ a /
    $className = str_replace('\\', '/', $className);

    // Le agregamos la extensión de php, y la carpeta de
    // base "app/".
    $filepath = "/web/html/classesUSAL/class_" . $className . ".php";

    // Verificamos si existe, y en caso positivo,
    // incluimos la clase.
    echo($filepath) . '----';

    var_dump(file_exists($filepath));

    echo($filepath) . '<br/><br/>';

    if (file_exists($filepath)) {
        require $filepath;
    }
});


