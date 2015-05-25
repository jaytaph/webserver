<?php

spl_autoload_register(function ($class) {
    $prefix = 'WebServer\\';
    $base_dir = __DIR__ . '/WebServer/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});


$host = isset($argv[1]) ? $argv[1] : "127.0.0.1";
$port = isset($argv[2]) ? $argv[2] : 4000;
$docroot = isset($argv[3]) ? $argv[3] : "docroot";
$index = isset($argv[4]) ? $argv[4] : "index.html";

$ws = new WebServer\WebServer($host, $port, $docroot, $index);
$ws->serve();
