<?php

require_once '../lib/Autoload.php';

$cssFile = array_diff(scandir('css'), ['.', '..'])[2];
$loader = new \Twig\Loader\FilesystemLoader('../app/view/');
$twig = new \Twig\Environment($loader, []);

echo $twig->render(
    'index/index.html.twig',
    [
        'CSS_FILE' => $cssFile,
        'name' => 'Carlos'
    ]
);
