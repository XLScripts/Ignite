<?php namespace Ignite\Components;

/*
* A Boilerplate Abstraction layer between Twig and the application.
* Makes working with twig a bit cleaner and easier.
*/
class TemplateRenderer {
    private $loader;
    private $twig;

    public function __construct($theme, $config, $templates_path = APP_PATH . 'Backend/Theme', $globals = []) {
        $this->theme  = $theme;
        $this->config = $config;
        $this->loader = new \Twig\Loader\FilesystemLoader($templates_path);
        $this->twig   = new \Twig\Environment($this->loader);

        $this->addGlobals($globals);

        $backend_filter = new \Twig\TwigFilter('backend_assets', function($string) {
            return $this->config->app['base_url'] . STORAGE_PATH . 'backend/' . $string;
        });

        $content_filter = new \Twig\TwigFilter('content', function($string) {
            return $this->config->app['base_url'] . STORAGE_PATH . 'app/' . $string;
        });

        $theme_filter = new \Twig\TwigFilter('theme', function($string) {
            return $this->config->app['base_url'] . 'themes/' . $this->theme . '/' . $string;
        });

        $page_filter = new \Twig\TwigFilter('page', function($string) {
            return $this->config->app['base_url'] . trim($string, '/');
        }, [ 'is_safe' => ['html'] ]);

        $backend_route_filter = new \Twig\TwigFilter('backend', function($string) {
            return $this->config->app['base_url'] . $this->config->cms['backend_route'] . trim($string, '/');
        }, [ 'is_safe' => ['html'] ]);

        $fragment_filter = new \Twig\TwigFilter('fragment', function($string) {
            echo $this->twig->render('fragments/' . $string);
        }, ['is_safe' => ['html']]);

        $this->twig->addFilter($backend_filter);
        $this->twig->addFilter($content_filter);
        $this->twig->addFilter($theme_filter);
        $this->twig->addFilter($fragment_filter);
        $this->twig->addFilter($page_filter);
        $this->twig->addFilter($backend_route_filter);

    }

    /*
    * Add multiple global varaibles.
    */
    public function addGlobals($globals) {
        if($globals && count($globals) > 0) {
            foreach($globals as $global => $value) {
                $this->twig->addGlobal(
                    $global,
                    $value
                );
            }
        }
    }

    /*
    * Add a single Global.
    */
    public function addGlobal($global, $value) {
        $this->twig->addGlobal($global, $value);
    }

    public function safeName($name) {
        if(substr_compare($name, '.htm', -strlen('.htm')) == 0) {
            return $name;
        } else
            return $name . '.htm';
    }

    private function getPluginData($handler, $params, $vars) {
        $split = explode('::', $handler);
        $plugin = $split[0];
        $method = $split[1];

        $value = null;

        try {
            $plugin = new $plugin($this->config, new IncomingRequest(), new OutgoingResponse());
            $value  = $plugin->{$method}($params, $vars);
        } catch(Exception $e) {
            throw $e;
        }

        return $value;
    }

    private function callPluginHandler($handler, $params, $vars) {
        $split = explode('::', $handler);
        $plugin = $split[0];
        $method = $split[1];

        try {
            $plugin = new $plugin($this->config, new IncomingRequest(), new OutgoingResponse());
            $plugin->{$method}($params, $vars);
        } catch(Exception $e) {
            throw $e;
        }

        return null;
    }

    public function render_page($name) {
        return $this->twig->render(
            $this->safeName($name)
        );
    }

    public function render_layout($name, $content) {
        return $this->twig->render($this->safeName($name), [
            'content' => $content
        ]);
    }

    public function render($route_info, $params) {
        if($route_info['options']['handler'])
            $this->callPluginHandler($route_info['options']['handler'], $params, $route_info['options']['vars']);

        else {
            $data = [];
            if(is_array($route_info['options']['data'])) {
                foreach($route_info['options']['data'] as $field => $handler) {
                    $data[$field] = $this->getPluginData($handler, $params, $route_info['options']['vars']);
                }
            } else $data = $this->getPluginData($route_info['options']['data'], $params, $route_info['options']['vars']);
    
            $this->twig->addGlobal('page', [
                'path'    => $route_info['path'],
                'title'   => $route_info['options']['title'],
                'params'  => $params,
                'vars'    => $route_info['options']['vars'],
                'data'    => $data,
                'dynamic' => count($data) > 0
            ]);
    
            $page = $this->render_page($route_info['options']['view']);
    
            if($route_info['options']['layout']) {
                return $this->render_layout($route_info['options']['layout'], $page);
            } else
                return $page;
        }
    }
}