<?php

namespace App\Libraries;
use FastRoute\DataGenerator;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser;

class ExtendedDispatcher extends RouteCollector implements Dispatcher
{
    private $dispatcher;
    private $dispatcherFactory;
    public function __construct(
        RouteParser $parser,
        DataGenerator $generator,
        callable $dispatcherFactory
    ) {
        parent::__construct($parser, $generator);
        $this->dispatcherFactory = $dispatcherFactory;
    }
    public function dispatch($httpMethod, $uri)
    {
        if (null === $this->dispatcher) {
            $this->dispatcher = call_user_func(
                $this->dispatcherFactory,
                $this->getData()
            );
        }
        return $this->dispatcher->dispatch($httpMethod, $uri);
    }
    public function addRoute($httpMethod, $route, $handler, $middlewares = null)
    {
        $middlewares = null !== $middlewares ? ",$middlewares" : '';
        parent::addRoute($httpMethod, $route, "$handler@DefaultMiddleware$middlewares");
    }
    public function addGroupRoute($prefix, array $routes, $groupMiddlewares = null)
    {
        foreach ($routes as $key => $route) {
            $routeMethod = $route[0];
            $routePath = $route[1];
            $routeController = $route[2];
            $routeMiddlewares = $route[3] ?? null;
            $combinedMiddlewares = [];
            if (null !== $routeMiddlewares) {
                array_push($combinedMiddlewares, $routeMiddlewares);
            }
            if (null !== $groupMiddlewares) {
                array_push($combinedMiddlewares, $groupMiddlewares);
            }
            $combinedMiddlewares = implode(',', $combinedMiddlewares);
            $combinedMiddlewares = $combinedMiddlewares === '' ? null : $combinedMiddlewares;
            $this->addRoute($routeMethod, "$prefix$routePath", $routeController, $combinedMiddlewares);
        }
    }
}