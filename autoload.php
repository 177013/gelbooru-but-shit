<?php

spl_autoload_register(function(string $class): void {
    $paths = [
        __DIR__ . "/classes/$class.class.php",
        __DIR__ . "/classes/$class.php",
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
        }
    }
});
