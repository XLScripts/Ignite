<?php namespace Ignite\Router;

class Route {
    public $success = true;
    public $code    = 200;
    public $params  = [];

    public function __construct(
        $success, $code, $handler = null, $params = []
    ) {
        $this->success       = $success;
        $this->code          = $code;
        $this->route_handler = $handler;
        $this->params        = $params;

        return;
    }

    public function handler() {
        if($this->route_handler) {
            $handler = $this->route_handler;
            return $handler();
        }
        return null;
    }
}