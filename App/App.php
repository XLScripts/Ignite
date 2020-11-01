<?php namespace Ignite;

/*
* The Base Application class.
* This class controls the flow of the entire application.
* You may extend this class to add additional functionality.
*/
class App {
    protected $config;
    protected $database;

    protected $request;
    protected $response;

    protected $template_renderer;

    /*
    * Properties related to the currently Selected Theme. Applies for both Frontend Themes and Backend Themes.
    * 1 - $meta :- An array containing some information about the theme. 
    *     -- $meta['theme'] contains the information
    *     -- $meta['settings] contains the Schema for the Theme User Preferences (Editable in the Backend)
    * 2 - $pages :- Key-value pairs containing Routes and their options e.g. view, layout, dynamic, data, etc.
    * 3 - $settings_data :- The currently selected User Preferences based on the schema defined in $meta['settings']
    */
    protected $meta          = [];
    protected $pages         = [];
    protected $settings_data = [];

    public function __construct() {
        /*
        * -> Load Configuration Files.
        * -> Establish Database Connection.
        */
        $this->config   = new Config();
        $this->database = new Database\Connection($this->config->database);

        /*
        * Create an \Ignite\Components\IncomingRequest Object - Used for obtaining information about the request.
        *Create an \Ignite\Components\IncomingRequest Object - Used for composing Outgoing Responses to the client.
        */
        $this->request  = new Components\IncomingRequest();
        $this->response = new Components\OutgoingResponse(); 

        /*
        * Check if the current URI starts with the Backend Route.
        * This is used for routing / loading modules related to a certain area rather than everything
        */
        $this->is_backend = substr(trim($this->request->getUri(), '/'), 0, strlen($this->config->cms['backend_route'])) == $this->config->cms['backend_route'];
    }

    private function initialize_frontend() {
        $theme = $this->config->cms['theme'];

        if(
            Helpers\file_exists_critical(BASE_PATH . 'themes/' . $theme . '/src/meta.php')
            && Helpers\file_exists_critical(BASE_PATH . 'themes/' . $theme . '/src/pages.php')
        ) {
            $this->meta  = require_once(BASE_PATH . 'themes/' . $theme . '/src/meta.php');
            $this->pages = require_once(BASE_PATH . 'themes/' . $theme . '/src/pages.php');
        }

        if(isset($this->meta['requires']) && is_array($this->meta['requires'])) {
            foreach($this->meta['requires'] as $class) {
                if(!class_exists($class))
                    throw new \Exception($class . ' does not exist. ' . $this->meta['theme']['name'] . ' requires this in-order to work.');
            }
        }

        $settings_data = Services\DataLoader::Theme($this->meta);

        $this->template_renderer = new Components\TemplateRenderer(
            $theme,
            $this->config,
            BASE_PATH . 'themes/' . $theme . '/src',
            [
                'theme' => [
                    'meta' => $this->meta['theme'],
                    'settings' => $settings_data
                ],
                'config' => $this->config
            ]
        );
    }

    private function initialize_backend() {
        if(
            Helpers\file_exists_critical(APP_PATH . 'Backend/Theme/meta.php')
            && Helpers\file_exists_critical(APP_PATH . 'Backend/Theme/pages.php')
        ) {
            $this->meta  = require_once(APP_PATH . 'Backend/Theme/meta.php');
            $this->pages = require_once(APP_PATH . 'Backend/Theme/pages.php');
        }

        if(isset($this->meta['requires']) && is_array($this->meta['requires'])) {
            foreach($this->meta['requires'] as $class) {
                if(!class_exists($class))
                    throw new \Exception($class . ' does not exist. ' . $this->meta['theme']['name'] . ' requires this in-order to work.');
            }
        }

        $settings_data = Services\DataLoader::Theme($this->meta);

        $this->template_renderer = new Components\TemplateRenderer(
            'backend',
            $this->config,
            APP_PATH . 'Backend/Theme',
            [
                'theme' => [
                    'meta' => $this->meta['theme'],
                    'settings' => $settings_data
                ],
                'config' => $this->config
            ]
        );
    }

    protected function traverse_nodes($pages, $routes) {
        foreach($pages as $path => $passed_options) {
            $options = [
                "type"   => 'route',
                "method" => 'GET',
                "view"   => null,
                "layout" => null,
                "data"   => [],
                "vars"   => [],
                "handler" => null,
                "title"  => 'default',
                "routes" => [],
            ];

            if(is_array($passed_options)) {
                foreach($passed_options as $option => $value) {
                    $options[$option] = $value;
                }
            } else 
                $options['handler'] = $passed_options;

            if($options['type'] == 'group') {
                $routes->addGroup(Helpers\route_name($path), function($r) use ($options) {
                    $this->traverse_nodes($options['routes'], $r);
                });
            } else {
                $routes->addRoute($options['method'], Helpers\route_name($path), function() use ($path, $options) {
                    return [
                        'path'    => $path,
                        'options' => $options
                    ];
                });   
            }
        }
    }

    protected function establish_routes() {
        $this->router = new Router\RouteDispatcher(
            $this->config->app['base_url'],
            function(\FastRoute\RouteCollector $routes) {
                $this->traverse_nodes($this->pages, $routes);
            }
        );
    }

    public function run() {
        /*
        * If the current route is a Backend Route, then we only load files with the Backend.
        */
        if($this->is_backend)
            $this->initialize_backend();
        /*
        * Else we load the files related to the currently selected theme.
        */
        else
            $this->initialize_frontend();

        $this->establish_routes();

        /*
        * The dispatch method will take in the URI & Method. It returns information about the current route.
        */
        $this->router->dispatch(
            $this->request->getMethod(),
            $this->request->getUri()
        );

        /*
        * If the Route matches properly, we call the route handler that returns the Path / Templates to render.
        * This information is passed to the template renderer which returns an HTML String.
        * This string is then set as the response body, and the response is sent to the Client.
        */
        if($this->router->current->success) {
            $route_info = $this->router->current->handler();
            $this->response->setBody(
                $this->template_renderer->render(
                    $route_info,
                    $this->router->current->params
                )
            );
        }
        /*
        * Else, we report some errors.
        */
        else 
            $this->response->setStatus(404, 'Not Found')->setBody('Not Found.');

        $this->response->send();
    }
}