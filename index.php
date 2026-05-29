<?php
session_start();
define('BASE_PATH', __DIR__);
define('BASE_URL', (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));

require_once BASE_PATH.'/config/database.php';
require_once BASE_PATH.'/core/Security.php';
require_once BASE_PATH.'/core/Controller.php';

$page   = preg_replace('/[^a-z0-9_]/', '', strtolower($_GET['page']   ?? 'home'));
$action = preg_replace('/[^a-z0-9_]/', '', strtolower($_GET['action'] ?? 'index'));

// Route admin
if ($page === 'admin') {
    require_once BASE_PATH.'/controllers/AdminController.php';
    $ctrl = new AdminController();
    $method = $action ?: 'dashboard';
    if (method_exists($ctrl, $method)) $ctrl->$method();
    else $ctrl->dashboard();
    exit;
}

$routes = [
    'home'       => 'HomeController',
    'pharmacie'  => 'PharmacieController',
    'medicament' => 'MedicamentController',
    'blog'       => 'BlogController',
    'auth'       => 'AuthController',
    'commande'   => 'CommandeController',
    'contact'    => 'ContactController',
    'don'        => 'DonController',
    'newsletter' => 'NewsletterController',
];

if (!isset($routes[$page])) {
    http_response_code(404);
    require BASE_PATH.'/views/errors/404.php';
    exit;
}

$file = BASE_PATH.'/controllers/'.$routes[$page].'.php';
if (!file_exists($file)) { http_response_code(404); require BASE_PATH.'/views/errors/404.php'; exit; }
require_once $file;
$ctrl = new $routes[$page]();
if (method_exists($ctrl, $action)) $ctrl->$action();
else $ctrl->index();
