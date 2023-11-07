<?php

spl_autoload_register(static function ($className) {
    $fullName = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
        . implode(DIRECTORY_SEPARATOR, explode('\\', $className))
        . '.php';

    if (file_exists($fullName)) {
        require_once($fullName);
    }
});
