<?php namespace Ignite;

use Illuminate\Database\Capsule\Manager as IlluminateCapsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class App {
    private $uri;
    private $method;

    private $router;
    private $config;

    private $theme;
    private $theme_meta;
    private $theme_pages;
    private $theme_data;

    private $twig_loader;
    private $twig;

    private $page_map;

    public $database;

    public function __construct() {
        $this->initialize_config();
        $this->initialize_database();
        $this->initialize_theme();
        $this->initialize_twig();
        $this->initialize_router();
    }

    private function initialize_config() {
        $this->uri    = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD']; 

        $this->config   = new Config();
    }

    private function initialize_database() {
        $this->database = new IlluminateCapsule;
        $this->database->addConnection(
            $this->config->database
        );

        $this->database->setEventDispatcher(
            new Dispatcher(
                new Container
            )
        );

        $this->database->setAsGlobal();
        $this->database->bootEloquent();
    }

    private function initialize_theme() {
        $this->theme = $this->config->cms['theme'];

        if(
            \file_exists(BASE_PATH . 'themes/' . $this->theme . '/src/meta.php') 
            && \file_exists(BASE_PATH . 'themes/' . $this->theme . '/src/pages.php')
        ) {
            $this->theme_meta  = require_once(BASE_PATH . 'themes/' . $this->theme . '/src/meta.php');
            $this->theme_pages = require_once(BASE_PATH . 'themes/' . $this->theme . '/src/pages.php');
        } else
            throw new \Exception("Files Not Found: meta.json & pages.json should be present inside the theme's source directory.");

        // print_r([
        //     'theme' => $this->theme,
        //     'settings'  => $this->theme_meta,
        //     'pages'     => $this->theme_pages
        // ]);
        // exit;

        $this->theme_data = Helpers\theme_data($this->theme, $this->theme_meta['settings']);
    }

    private function initialize_twig() {
        $this->twig_loader = new \Twig\Loader\FilesystemLoader(BASE_PATH . 'themes/' . $this->theme . '/src');
        $this->twig        = new \Twig\Environment($this->twig_loader/*, [
            'cache' => STORAGE_PATH . 'sys/cache/twig'
        ]*/);

        $backend_filter = new \Twig\TwigFilter('backend_assets', function($string) {
            return $this->config->app['base_url'] . STORAGE_PATH . 'backend/' . $string;
        });

        $content_filter = new \Twig\TwigFilter('content', function($string) {
            return $this->config->app['base_url'] . STORAGE_PATH . 'app/' . $string;
        });

        $theme_filter = new \Twig\TwigFilter('theme', function($string) {
            return $this->config->app['base_url'] . 'themes/' . $this->theme . '/' . $string;
        });

        $fragment_filter = new \Twig\TwigFilter('fragment', function($string) {
            echo $this->twig->render('fragments/' . $string);
        }, ['is_safe' => ['html']]);

        $page_filter = new \Twig\TwigFilter('page', function($string) {
            return $this->config->app['base_url'] . trim($string, '/');
        }, [ 'is_safe' => ['html'] ]);

        $backend_route_filter = new \Twig\TwigFilter('backend', function($string) {
            return $this->config->app['base_url'] . $this->config->cms['backend_route'] . trim($string, '/');
        }, [ 'is_safe' => ['html'] ]);

        $this->twig->addFilter($backend_filter);
        $this->twig->addFilter($content_filter);
        $this->twig->addFilter($theme_filter);
        $this->twig->addFilter($fragment_filter);
        $this->twig->addFilter($page_filter);
        $this->twig->addFilter($backend_route_filter);

        $this->twig->addGlobal('theme', [ 'meta' => $this->theme_meta['theme'], 'settings' => $this->theme_data ]);
        $this->twig->addGlobal('config', $this->config);
    }

    private function render_page($path, $options, $return = true) {
        $data = $this->twig->render(
            $options['view'],
            [
                'path'  => $path,
                'title' => $options['title']
            ]
        );

        if(!$return)
            echo $data;

        return $data;
    }

    private function render_layout($layout, $content, $return = true) {
        $data = $this->twig->render('layouts/' . $layout, [
            'content' => $content
        ]);

        if(!$return)
            echo $data;

        return $data;
    }

    private function render($params, $path, $options) {
        if(isset($options['dynamic']) && $options['dynamic']) {
            $data = [];
            foreach($options['data'] as $field => $resolver) {
                $split = explode('::', $resolver);
                $plugin = $split[0];
                $method = $split[1];

                $plugin = new $plugin();

                $data[$field] = $plugin->{$method}($params);
            }

            $this->twig->addGlobal('data', $data);
        }

        $this->twig->addGlobal('uri_params', $params);
        $this->twig->addGlobal('page', [ 'path' => $path, 'title' => $options['title'], 'dynamic' => isset($options['dynamic']) && $options['dynamic'] ? true : false ]);

        $page = $this->render_page($path, $options);

        if(isset($options['layout'])) {
            echo $this->render_layout($options['layout'], $page);
        } else
            echo $page;
    }

    private function initialize_router() {
        $this->router = new Router\RouteDispatcher(
            $this->config->app['base_url'],
            function(\FastRoute\RouteCollector $routes) {
                foreach($this->theme_pages as $path => $options) {
                    if(isset($options['view'])) {
                        $m = isset($options['method']) ? $options['method'] : 'GET';
                        $routes->addRoute($m, $path, function() use ($path, $options) {
                            return [
                                'path' => $path,
                                'options' => $options
                            ];
                        });   
                    }
                }
            }
        );
    }

    public function run() {
        $this->router->dispatch(
            $this->method,
            $this->uri
        );

        if($this->router->current['success']) {
            $route = $this->router->current['handler']();
            $this->render($this->router->current['params'], $route['path'], $route['options']);
        }
    }
}