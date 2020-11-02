<?php namespace Plugins\Demonicious;

class OrderTracker extends \Ignite\Base\Plugin {
    protected $renderer  = null;
    
    protected $name      = 'OrderTracker Plugin';
    protected $shortcode = 'demonicious-orders-tracker';

    public function initialize() {
        $this->renderer = new \Ignite\Components\TemplateRenderer('plugin', $this->config, __DIR__ . '/OrderTracker/Templates');
    }

    function data() {
        $data = \json_decode(\file_get_contents('https://jsonplaceholder.typicode.com/todos'), true);
        return $data;
    }

    function widget() {
        // $stats = Models\User::all();

        return $this->renderer->render_file('admin_widget', [
            'data' => 'DATA:FROM:DB'
        ]);
    }
}