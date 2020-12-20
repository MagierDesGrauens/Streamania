<?php

echo '  -> Compling js...' . PHP_EOL;

$jsContent = '';
$jsPlugins = explode("\n", file_get_contents(__DIR__ . '/../app/src/js/app.js'));

foreach ($jsPlugins as $plugin) {
    $file = __DIR__ . '/../app/src/js/' . $plugin;

    if (file_exists($file) && !is_dir($file)) {
        $jsContent .= file_get_contents($file) . PHP_EOL;
    }
}

echo '  -> Writing js...' . PHP_EOL;

if (!file_exists(__DIR__ . '/../public/js')) {
    mkdir(__DIR__ . '/../public/js');
}

$jsFiles = array_diff(scandir(__DIR__ . '/../public/js'), ['.', '..']);

// Alte JS Dateien löschen
foreach ($jsFiles as $file) {
    unlink(__DIR__ . '/../public/js/' . $file);
}

// Neue JS Datei schreiben
file_put_contents(__DIR__ . '/../public/js/script' . time() . '.js', $jsContent);
