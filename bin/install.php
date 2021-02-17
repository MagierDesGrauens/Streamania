<?php

use \Streamania\Installer;

include_once __DIR__ . '/../lib/Autoload.php';

$action = $argv[1] ?? '';
$installer = new Installer();

$methods = get_class_methods($installer);
$methodFound = false;

if (method_exists($installer, 'install' . ucfirst($action))) {
    $installer->install($action);
} else {
    printf(
        'Syntax: %sPossible actions:',
        $syntax . PHP_EOL . PHP_EOL
    );

    foreach ($methods as $method) {
        if (substr($method, 0, 7) === 'install') {
            printf(strtolower(substr($method, 7, strlen($method) - 6) . PHP_EOL));
        }
    }
}
