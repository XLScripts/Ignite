<?php namespace Ignite\Router;

class RouteDispatcher {
    private $route_dispatcher;
    public $current;

    public function __construct($base_url = 'http://localhost/', \Closure $collection) {
        $this->route_dispatcher = \FastRoute\simpleDispatcher(
            $collection
        );
    }

    public function dispatch($method, $uri) {
        if(false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri  = \trim(\rawurldecode($uri));
        if(strlen($uri) > 1)
            $uri = trim($uri, '/');

        $info = $this->route_dispatcher->dispatch(
            $method, $uri
        );

        switch($info[0]) {
            case \FastRoute\Dispatcher::FOUND:
                $this->current = new Route(true, 200, $info[1], $info[2]);
            break;
            case \FastRoute\Dispatcher::NOT_FOUND:
                $this->current = new Route(false, 404);
            break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $this->current = new Route(false, 405);
            break;
            default:
                $this->current = new Route(false, 500);
            break;
        }
    }
}