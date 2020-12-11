<?php

require_once '../lib/Autoload.php';

$cssFile = array_diff(scandir('css'), ['.', '..'])[2];
$loader = new \Twig\Loader\FilesystemLoader('../app/view/');
$twig = new \Twig\Environment($loader, []);
$site = ucfirst($_GET['site'] ?? 'index');
$action = ucfirst($_GET['action'] ?? 'index');
$controllerPath = '../app/controller/' . $site . 'Controller.php';
$siteController = $site . 'Controller';
$modelPath = '../app/model/' . $site . 'Model.php';
$siteModel = $site . 'Model';
$viewData = [];

if (file_exists($controllerPath))
{
    include_once $controllerPath;
}

if (file_exists($modelPath))
{
    include_once $modelPath;
}

$model = new $siteModel();
$controller = new $siteController($model);
$renderData = [
    'CSS_FILE' => $cssFile
];

if (method_exists ($controller, $action . 'Action')) {
    $controller->{$action . 'Action'}();
}

if (method_exists($model, $action . 'View')) {
    $viewData = $model->{$action . 'View'}();
}

if (!empty($viewData)) {
    foreach ($viewData[1] as $key => $value) {
        $renderData[$key] = $value;
    }

    echo $twig->render(
        $viewData[0],
        $renderData
    );
} else {
    echo $twig->render('error/404.html.twig', $renderData);
}
