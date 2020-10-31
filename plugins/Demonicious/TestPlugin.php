<?php namespace App\Demonicious;

class TestPlugin extends \Ignite\Components\PluginBase {
    protected $name      = 'TestPlugin';
    protected $shortcode = 'demonicious-testplugin';

    protected function initialize() {
        $this->configureSettings([
            'name' => [
                'type' => 'string',
                'default' => 'Test'
            ]
        ]);
    }

    public function data($data) {
        print_r($data);

        return 'hello';
    }

    public function todos() {
        return json_decode(file_get_contents('https://jsonplaceholder.typicode.com/todos'), true);
    }
}