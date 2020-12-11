<?php

require_once __DIR__ . '/../lib/Sass/scss.inc.php';

use ScssPhp\ScssPhp\Compiler;

$css = '';
$scss = new Compiler();

echo '  -> Compling scss...' . PHP_EOL;

try {
    $css = $scss->compile('@import "' . __DIR__ . '/../app/src/scss/app.scss";');
} catch (\Exception $e) {
    echo PHP_EOL . $e->getMessage();
    die();
}

echo '  -> Writing css...';

if (!file_exists(__DIR__ . '/../public/css')) {
    mkdir(__DIR__ . '/../public/css');
}

$styleFiles = array_diff(scandir(__DIR__ . '/../public/css'), ['.', '..']);

foreach ($styleFiles as $file) {
    unlink(__DIR__ . '/../public/css/' . $file);
}

file_put_contents(__DIR__ . '/../public/css/style' . time() . '.css', $css);
