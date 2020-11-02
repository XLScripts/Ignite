<?php namespace Plugins\Demonicious;

class Test extends \Ignite\Base\Plugin {
    protected $name = 'Test Plugin';
    protected $shortcode = 'ignite-test-plugin';

    public function data() {
        $this->response->setStatus(200, 'OK');
        $this->response->json([
            'data' => [
                'something'
            ]
        ]);

        $this->response->send();
    }

    public function about($uri_params, $vars) {
        return [
            'username' => 'test',
            'password' => 'test123'
        ];
    }
}