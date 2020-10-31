<?php namespace Ignition\Components;

class PluginBase {
    protected $settings = [];
    
    protected $name      = null;
    protected $shortcode = null;
    
    public function __construct() {
        if(!$this->name || !$this->shortcode) {
            throw new \Exception('Invalid `name` OR `shortcode` property in Plugin.');
        }
    }

    public function configureSettingsForm($config) {
        $this->settings = \Ignition\Helpers\plugin_data($this->shortcode, $config);
    }
}