<?php namespace Ignite\Database;

use Illuminate\Database\Capsule\Manager as IlluminateCapsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Connection {
    public function __construct($cfg = null) {
        $this->database = new IlluminateCapsule;
        $this->database->addConnection(
            $cfg ? $cfg : \Ignite\Config::Load('database')
        );

        $this->database->setEventDispatcher(
            new Dispatcher(
                new Container
            )
        );

        $this->database->setAsGlobal();
        $this->database->bootEloquent();
    }
}