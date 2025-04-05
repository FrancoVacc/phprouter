<?php
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/controller/userController.php';
require_once __DIR__ . '/controller/productController.php';
require_once __DIR__ . '/controller/categoryController.php';

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use MiladRahimi\PhpRouter\Router;
use MiladRahimi\PhpRouter\View\View;




$router = Router::create();

// $router->setupView(__DIR__ . '/views');

// $router->get('/notfound', function (View $view) {
//     return $view->make('404.php');
// });

$router->get('/', function () {
    return new JsonResponse(['message' => 'ok']);
});


//? Users
$router->get('/users', [userController::class, 'getUsers']);
$router->get('/users/{id}', [userController::class, 'getUser']);
$router->post('/users', [userController::class, 'newUser']);
$router->put('/users/{id}', [userController::class, 'updateUser']);
$router->delete('/users/{id}', [userController::class, 'deleteUser']);

//?Products
$router->get('/products', [productController::class, 'index']);
$router->get('/products/barcode/{barcode}', [productController::class, 'getProduct']);
$router->get('/products/name/{name}', [productController::class, 'getName']);
$router->post('/products', [productController::class, 'newProduct']);
$router->put('/products/{id}', [productController::class, 'updateProduct']);
$router->delete('/products/{id}', [productController::class, 'destroyProduct']);

//? categorias
$router->get('/categories', [categoryController::class, 'getAll']);

$router->dispatch();
