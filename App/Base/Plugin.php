<?php namespace Ignite\Base;

class Plugin {
    protected $name      = 'Plugin Base';
    protected $shortcode = 'ignition-plugin-base';

    protected $settings_schema;
    protected $settings = [];
    
    protected $config;
    protected $request;
    protected $response;

    public function __construct($config, $request, $response) {
        if(!$this->name || !$this->shortcode) {
            throw new \Exception('Invalid `name` OR `shortcode` property in Plugin.');
        }

        $this->config = $config;
        $this->request = $request;
        $this->response = $response;

        $this->initialize();
    }

    protected function initialize() {}

    protected function configureSettings($config) {
        $this->settings_schema = $config;
        $this->settings = \Ignite\Services\DataLoader::Plugin($this->shortcode, $this->settings_schema);
    }
}