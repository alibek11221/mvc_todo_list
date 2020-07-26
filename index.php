<?php

use App\MiddleWare\AuthMiddleWare;
use DI\ContainerBuilder;
use Pecee\SimpleRouter\SimpleRouter;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

require_once __DIR__.'/vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = (new ContainerBuilder())
    ->useAutowiring(true)
    ->useAnnotations(true)
    ->build();

$container->set(LoaderInterface::class, new FilesystemLoader(__DIR__.'/src/Views'));

SimpleRouter::enableDependencyInjection($container);
SimpleRouter::setDefaultNamespace('App\Controllers');

SimpleRouter::group(
    ['prefix' => $_ENV['DIRECTORY_ROOT']],
    static function () {
        /**
         * @see \App\Controllers\TodoController::getAllByPage()
         */
        SimpleRouter::get('/{page?}', 'TodoController@getAllByPage')->where(['page' => '[0-9]+']);
        /**
         * @see \App\Controllers\TodoController::updateStatus()
         */
        SimpleRouter::get('/todo/done/{id}/{isdone}', 'TodoController@updateStatus')->where(['id' => '[0-9]+']);
        /**
         * @see \App\Controllers\TodoController::create()
         */
        SimpleRouter::post('/todo/create', 'TodoController@create');
        /**
         * @see \App\Controllers\UserController::login()
         */
        SimpleRouter::post('/login', 'UserController@login');
        /**
         * @see \App\Controllers\UserController::loginPage()
         */
        SimpleRouter::get('/login', 'UserController@login');

        SimpleRouter::group(
            ['middleware' => AuthMiddleWare::class],
            static function () {
                /**
                 * @see \App\Controllers\UserController::logout()
                 */
                SimpleRouter::get('/logout', 'UserController@logout');
                /**
                 * @see \App\Controllers\TodoController::update()
                 */
                SimpleRouter::get('/todo/update/{id}', 'TodoController@update');
                /**
                 * @see \App\Controllers\TodoController::update()
                 */
                SimpleRouter::post('/todo/update/{id}', 'TodoController@update');
            }
        );
    }
);

SimpleRouter::start();





