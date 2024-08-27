<?php

namespace App;

use App\Libraries\Database;
use Dotenv\Dotenv;
use App\Libraries\ExtendedDispatcher;
use FastRoute;
use FastRoute\DataGenerator;
use FastRoute\Dispatcher;
use FastRoute\RouteParser;

class Bootstrap
{
    public function __construct()
    {
        // Load environment variables
        $envDir = dirname(__DIR__);
        $dotenv = Dotenv::createImmutable($envDir);
        $dotenv->load();
        // Initianize database connection and create tables
        Database::init();
        Database::runSchemaBuilder();
    }
    public function run()
    {
        $dispatcher = new ExtendedDispatcher(
            new RouteParser\Std,
            new DataGenerator\GroupCountBased,
            function ($data) {
                return new Dispatcher\GroupCountBased($data);
            }
        );
        // Define routes and controllers
        // $dispatcher->addRoute('GET', '/debug', 'DebugController@debug@MethodMiddleware,QueryMiddleware');
        $dispatcher->addGroupRoute('/debug', [
            ['GET', '', 'DebugController@debug']
        ], 'MethodMiddleware,QueryMiddleware');
        // Requests processing, don't care about this
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        if (false !== $position = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $position);
        }
        $requestUri = rawurldecode($requestUri);
        $routeInfo = $dispatcher->dispatch($requestMethod, $requestUri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $args = array_values($vars);
                $handlerParts = explode('@', $handler);
                $controllerName = $handlerParts[0];
                $action = $handlerParts[1];
                $middlewares = $handlerParts[2] ?? null;
                if (null !== $middlewares) {
                    $middlewareParts = explode(',', $middlewares);
                    if ($middlewareParts) {
                        foreach ($middlewareParts as $middleware) {
                            $middlewareClass = "\\App\\Middlewares\\$middleware";
                            $middlewareCallback = [
                                new $middlewareClass,
                                'handle'
                            ];
                            call_user_func_array($middlewareCallback, []);
                        }
                    }
                }
                $controllerClass = "\\App\\Controllers\\$controllerName";
                $controller = [
                    new $controllerClass,
                    $action
                ];
                echo call_user_func_array($controller, $args);
                exit();
        }
    }
}