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
            $uri = rtrim($uri, '/');



        $info = $this->route_dispatcher->dispatch(
            $method, $uri
        );

        switch($info[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                $this->current = [
                    'code' => 404,
                    'message' => 'Not Found',
                    'success' => false
                ];
            break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $this->current = [
                    'code' => 405,
                    'message' => 'Method Not Allowed',
                    'success' => false
                ];
            break;
            case \FastRoute\Dispatcher::FOUND:
                $this->current = [
                    'success' => true,
                    'handler' => $info[1],
                    'params'  => $info[2]
                ];
            break;
            default:
                $this->current = [
                    'code' => 500,
                    'message' => 'Internal Server Error',
                    'success' => false
                ];
            break;
        }
    }
}