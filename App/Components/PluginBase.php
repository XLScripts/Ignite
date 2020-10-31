<?php namespace Ignite\Components;

class PluginBase {
    protected $settings = [];
    
    protected $name      = null;
    protected $shortcode = null;
    
    public function __construct() {
        if(!$this->name || !$this->shortcode) {
            throw new \Exception('Invalid `name` OR `shortcode` property in Plugin.');
        }

        $this->initialize();
    }

    protected function initialize() {}

    protected function configureSettings($config) {
        $this->settings = \Ignite\Helpers\plugin_data($this->shortcode, $config);
    }
}