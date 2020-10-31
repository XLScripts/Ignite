<?php namespace Ignite;

class Config {
    public $app;
    public $security;
    public $database;
    public $cms;

    public function __construct() {
        $this->app      = require_once(CFG_PATH . 'app.php');
        $this->security = require_once(CFG_PATH . 'security.php');
        $this->database = require_once(CFG_PATH . 'database.php');
        $this->cms      = require_once(CFG_PATH . 'cms.php');
    }

    public static function Load($name) {
        return require_once(CFG_PATH . $name . '.php');
    }
}