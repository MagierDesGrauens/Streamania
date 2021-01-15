<?php

use \Streamania\Database;
use \Streamania\Installer;

include_once __DIR__ . '/../lib/Autoload.php';

Database::connect();

$action = $argv[1] ?? '';
$installer = new Installer();

if ($action !== '--reset') {
    $installer->install($action);
} else {
    $resetAction = $argv[2] ?? '';

    if ($resetAction === '') {
        $methods = get_class_methods($installer);

        printf(
            'Syntax: install --reset <action> %sPossible actions: %s',
            PHP_EOL . PHP_EOL,
            PHP_EOL
        );

        foreach ($methods as $method) {
            if (substr($method, 0, 5) === 'reset') {
                printf(strtolower(substr($method, 5, strlen($method) - 4) . PHP_EOL));
            }
        }
    } else {
        if (method_exists($installer, 'reset' . $resetAction)) {
            $installer->{'reset' . $resetAction}();
        } else {
            printf('Action "%s" not found.', $resetAction);
        }
    }
}
